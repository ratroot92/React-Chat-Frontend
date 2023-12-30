/* eslint-disable security/detect-object-injection */
/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Router, Request, Response, NextFunction } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import { ProtectAdmin } from '../../passport';
import { GroupModel, IGroupDoc } from '../../models/Group';
import { UserModel, IUserDoc } from '../../models/User';
import mongoose from 'mongoose';
import { IPostDoc, PostModel } from '../../models/Post';
import { CommentModel } from '../../models/Comment';
import multer from 'multer';
import path from 'path';
import { ensureDir, fileUtils } from '../../helpers';
import Joi from 'joi';
import { IformattedErrors } from '../../dto/ApiResponse';
import ApiFeature from '../../helpers/api-feature';
import stringUtils from '../../helpers/string-utils';
import { FileModel } from '../../models/File';
import S3Util from '../../utils/s3';

const adminGroupController = {
  async createGroup(request: Request, response: Response): Promise<any> {
    try {
      let avatarUploaded: string | null = null;
      const fileFilter = (request: Request, file: any, cb: any) => (['.png', '.jpg'].includes(path.extname(file.originalname).toLowerCase()) ? cb(null, true) : cb(new Error('Only .png or .jpg files are allowed')));
      const storage = multer.diskStorage({
        destination: (request: Request, file: any, cb: any) => cb(null, `${process.cwd()}/public/group/avatar`),
        filename: (request: Request, file: any, cb: any) => cb(null, Date.now() + path.extname(file.originalname)),
      });
      const upload = multer({ storage, fileFilter }).single('avatar');
      //@ts-ignore
      upload(request, response, async (err: any) => {
        try {
          if (err) {
            if (err.storageErrors) {
              return ApiResponse.badRequest(request, response, err.message);
            }
            return ApiResponse.badRequest(request, response, `Failed to upload group avatar.`);
          }
          if (request.file) {
            if (request.file.path) {
              //@ts-ignore
              avatarUploaded = request.file.path.replace(process.cwd(), process.env.APP_DOMAIN);
            }
          }
          const name = request.body.name;
          const description = request.body.description;
          const isModerated = stringUtils.stringToBoolean(request.body.isModerated);
          const rawGroupMembers = request.body.groupMembers;
          const rawModeratorSettings = request.body.moderatorSettings;

          const payload: any = {
            name,
            description,
            isModerated,
            groupMembers: rawGroupMembers ? JSON.parse(rawGroupMembers) : [],
          };

          if (payload.isModerated) {
            const moderatorSettings = JSON.parse(rawModeratorSettings);
            payload.moderatorSettings = moderatorSettings;
            const moderatorId = moderatorSettings.moderator;
            const moderatorAlreadyIncluded = payload.groupMembers.some((member: any) => member.member === moderatorId);
            // Moderator will become supreme member
            if (!moderatorAlreadyIncluded) {
              payload.groupMembers.push({ canPost: moderatorSettings.canCreatePost, canComment: true, member: moderatorId });
            } else {
              // Make Moderator Permission Supreme
              payload.groupMembers = payload.groupMembers.map((member: any) => {
                if (member.member === moderatorId) {
                  return { canPost: moderatorSettings.canCreatePost, canComment: true, member: moderatorId };
                }
                return member;
              });
            }
          }

          const schema = Joi.object({
            name: Joi.string()
              // .pattern(/^[a-zA-Z\s]*$/)
              .min(3)
              .max(50)
              .required(),
            description: Joi.string().min(3).max(500).required(),
            // whoCanViewPost: Joi.string().valid('OWNER', 'GROUP_MEMBERS').required(),
            // whoCanPost: Joi.string().valid('OWNER', 'GROUP_MEMBERS').required(),
            isModerated: Joi.boolean().required(),
            moderatorSettings: Joi.when('isModerated', {
              is: true,
              then: Joi.object({
                moderator: Joi.string().alphanum().length(24).required(),
                canDeletePost: Joi.boolean().required(),
                canCreatePost: Joi.boolean().required(),
                canModifyClients: Joi.boolean().required(),
              }),
              otherwise: Joi.forbidden(),
            }).required(),

            groupMembers: Joi.array()
              .items(
                Joi.object({
                  member: Joi.string().alphanum().length(24).required(),
                  canPost: Joi.boolean().required(),
                  canComment: Joi.boolean().required(),
                })
              )
              .min(0)
              .required(),
          });
          const { error } = schema.validate(payload);
          if (error) {
            const formattedErrors: IformattedErrors[] = error.details.reduce((errorArr: IformattedErrors[], errDetails: any) => {
              errorArr.push({ field: errDetails.context.key, message: errDetails.message });
              return errorArr;
            }, []);
            //@ts-ignore
            avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
            return ApiResponse.unProcessibleContent(request, response, formattedErrors);
          } else {
            const groupAlreadyExists = await GroupModel.findOne({ name: payload.name });
            if (groupAlreadyExists) {
              //@ts-ignore
              avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
              return ApiResponse.badRequest(request, response, `Group with name '${payload.name}' already exists.`);
            }
            // const { isModerated, moderatorSettings, name, description, whoCanViewPost, whoCanPost, groupMembers } = payload;
            const { isModerated, moderatorSettings, name, description, groupMembers } = payload;

            const inValidGroupMembers: any[] = [];
            const validGroupMembers: any[] = [];

            const uniqueGroupMembers: any[] = groupMembers.reduce((uniqueGroupMembers: any, member: any) => {
              if (![...uniqueGroupMembers.map((m: any) => m.member)].includes(member.member)) {
                uniqueGroupMembers.push(member);
              }
              return uniqueGroupMembers;
            }, []);

            for (const member of uniqueGroupMembers) {
              const memberExist = await UserModel.findOne({ _id: member.member, isAdminApproved: true, role: 'user' });

              if (!memberExist) {
                inValidGroupMembers.push(member);
              } else {
                validGroupMembers.push(member);
              }
            }
            if (inValidGroupMembers.length > 0) {
              const invalidMembers = inValidGroupMembers.map((member) => member.member).join(',');
              //@ts-ignore
              avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
              return ApiResponse.badRequest(request, response, `Following group members are '${invalidMembers}'.`);
            }

            let moderatorUser: IUserDoc | null = null;
            if (isModerated) {
              moderatorUser = await UserModel.findOne({ _id: moderatorSettings.moderator, isAdminApproved: true });
              if (!moderatorUser) {
                //@ts-ignore
                avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
                return ApiResponse.badRequest(request, response, 'Moderator does not exist.');
              }
              if (moderatorUser.role === 'admin') {
                //@ts-ignore
                avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
                return ApiResponse.badRequest(request, response, `Moderator should be a client not admin.`);
              }
              //@ts-ignore
              if (moderatorUser._id === request.user._id) {
                //@ts-ignore
                avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
                return ApiResponse.badRequest(request, response, `Group Owner can not be a moderator.`);
              }
              moderatorSettings.moderator = new mongoose.Types.ObjectId(moderatorUser._id);
            }
            // validGroupMembers = validGroupMembers.map((member: any) => {
            //   member.createdAt = Date.now();
            //   return member;
            // });
            let group: IGroupDoc = new GroupModel({
              name,
              avatar: avatarUploaded ? avatarUploaded : '',
              description,
              // whoCanViewPost,
              // whoCanPost,
              isModerated,
              // @ts-ignore
              groupOwners: request.user._id,
              moderatorSettings: isModerated ? moderatorSettings : null,
              groupMembers: validGroupMembers,
              groupPosts: [],
            });
            await group.save();

            const newGroup: IGroupDoc | null = await GroupModel.findById(group._id)
              .select('_id name avatar description isModerated groupMembers moderatorSettings groupOwners updatedAt createdAt')
              .populate({
                path: 'groupMembers.member',
                select: '_id name email avatar phoneNo role country state',
              })
              .populate({
                path: 'moderatorSettings.moderator',
                select: '_id name email avatar phoneNo role country state',
              })
              .populate({
                path: 'groupOwners',
                select: '_id name email avatar phoneNo role country state',
              });

            if (newGroup) {
              group = newGroup;
            }
            return ApiResponse.ok(request, response, { group, message: 'Group created successfully.' });
          }
        } catch (err) {
          //@ts-ignore
          avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
          return ApiResponse.internalServerError(request, response, err);
        }
      });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async addGroupAvatar(request: Request, response: Response): Promise<any> {
    try {
      const groupId: string | undefined = request.params.id;
      if (!groupId) {
        return ApiResponse.badRequest(request, response, `Group id is required.`);
      }
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      } else {
        const fileFilter = (request: Request, file: any, cb: any) => (['.png', '.jpg'].includes(path.extname(file.originalname).toLowerCase()) ? cb(null, true) : cb(new Error('Only .png or .jpg files are allowed')));
        const storage = multer.diskStorage({
          destination: (request: Request, file: any, cb: any) => cb(null, `${process.cwd()}/public/group/avatar`),
          filename: (request: Request, file: any, cb: any) => cb(null, Date.now() + path.extname(file.originalname)),
        });
        const upload = multer({ storage, fileFilter }).single('avatar');
        //@ts-ignore
        upload(request, response, async (err: any) => {
          try {
            if (err) {
              if (err.storageErrors) {
                return ApiResponse.badRequest(request, response, err.message);
              }
              return ApiResponse.badRequest(request, response, `Failed to upload group avatar.`);
            }
            if (!request.file) {
              return ApiResponse.internalServerError(request, response, 'Group avatar is required.');
            }
            //@ts-ignore
            if (!group.avatar) {
              //@ts-ignore
              group = await GroupModel.findByIdAndUpdate(request.params.id, { $set: { avatar: request.file.path.replace(process.cwd(), process.env.APP_DOMAIN) } }, { new: true, upsert: false });
            } else {
              //@ts-ignore
              const avatarPath: string = group.avatar.replace(process.env.APP_DOMAIN, process.cwd());
              await fileUtils.autoUnlink(avatarPath);
              //@ts-ignore
              group = await GroupModel.findByIdAndUpdate(request.params.id, { $set: { avatar: request.file.path.replace(process.cwd(), process.env.APP_DOMAIN) } }, { new: true, upsert: false });
            }
            //@ts-ignore
            const newGroup: IGroupDoc | null = await GroupModel.findById(group._id)
              .populate({
                path: 'moderatorSettings.moderator',
                select: '_id name email avatar phoneNo role country state',
              })
              .populate({
                path: 'groupOwners',
                select: '_id name email avatar phoneNo role country state',
              })
              .populate({
                path: 'groupMembers.member',
                select: '_id name email avatar phoneNo role country state',
              })
              .populate({
                path: 'groupPosts',
                populate: [
                  {
                    path: 'media',
                  },
                ],
              });

            if (newGroup) {
              group = newGroup;
            }
            return ApiResponse.ok(request, response, { group, message: 'Success.' });
          } catch (err) {
            //@ts-ignore
            await fileUtils.autoUnlink(request.file.path);
            return ApiResponse.internalServerError(request, response, err);
          }
        });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async getGroups(request: Request, response: Response): Promise<any> {
    try {
      if (request.query.id) {
        const group: IGroupDoc | null = await GroupModel.findById(request.query.id)
          .select('_id name avatar description isModerated groupMembers moderatorSettings groupOwners updatedAt createdAt')
          .populate({
            path: 'groupMembers.member',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'moderatorSettings.moderator',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'groupOwners',
            select: '_id name email avatar phoneNo role country state',
          });
        if (!group) {
          return ApiResponse.badRequest(request, response, `Group not found.`);
        }
        return ApiResponse.ok(request, response, { group, message: 'Success.' });
      } else {
        const resultPerPage = 50;
        const apiFeature = new ApiFeature(
          GroupModel.find({})
            .select('_id name avatar description isModerated groupMembers moderatorSettings groupOwners updatedAt createdAt')
            .populate({
              path: 'groupMembers.member',
              select: '_id name email avatar phoneNo role country state',
            })
            .populate({
              path: 'moderatorSettings.moderator',
              select: '_id name email avatar phoneNo role country state',
            })
            .populate({
              path: 'groupOwners',
              select: '_id name email avatar phoneNo role country state',
            }),
          request.query
        )
          .search()
          .filter()
          .pagination(resultPerPage);
        const groups: IGroupDoc[] = await apiFeature.query;
        return ApiResponse.ok(request, response, { groups, message: 'Success.' });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async putGroup(request: Request, response: Response): Promise<any> {
    try {
      const { name } = request.body;

      let group: IGroupDoc | null = await GroupModel.findById(request.body._id).populate('moderatorSettings.moderator');
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      //@ts-ignore
      const adminId = request.user._id;
      const groupOwnerId = group.groupOwners.toString();
      if (adminId !== groupOwnerId) {
        return ApiResponse.badRequest(request, response, `Only group owner can update the group details.`);
      }
      // const UPDATEABLES = ['name', 'description', 'whoCanViewPost', 'whoCanPost', 'isModerated'];
      const UPDATEABLES = ['name', 'description', 'isModerated'];

      const set: Record<string, any> = Object.keys(request.body).reduce((set: Record<string, any>, param: string) => {
        if (UPDATEABLES.includes(param)) {
          set[param] = request.body[param];
        }
        return set;
      }, {});
      /**
       * case when he doesnot update name
       * then a group with same will always exists
       *
       * case when he changes name
       * then check if another group doesnot has same name
       */

      const groupExistWithOldName: IGroupDoc | null = await GroupModel.findOne({ name: { $regex: new RegExp(name, 'i') } });
      if (groupExistWithOldName) {
        if (groupExistWithOldName._id.toString() !== group._id.toString()) {
          return ApiResponse.badRequest(request, response, `Duplicate group 1.`);
        }
      }

      const groupExistWithNewName = await GroupModel.findOne({ name: { $regex: new RegExp(set.name, 'i') } });
      if (groupExistWithNewName) {
        if (groupExistWithNewName._id.toString() !== group._id.toString()) {
          return ApiResponse.badRequest(request, response, `Duplicate group 2`);
        }
      }

      if (!set.isModerated) {
        group = await GroupModel.findByIdAndUpdate({ _id: group._id }, { $set: set, $unset: { moderatorSettings: 1 } }, { new: true, upsert: false }).populate('moderatorSettings.moderator');
      } else {
        const moderatorUser: IUserDoc | null = await UserModel.findOne({ _id: request.body.moderatorSettings.moderator, role: 'user', isAdminApproved: true });
        if (!moderatorUser) {
          return ApiResponse.badRequest(request, response, 'Moderator does not exist.');
        }
        if (moderatorUser.role === 'admin') {
          //@ts-ignore
          avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
          return ApiResponse.badRequest(request, response, `Moderator should be a client not admin.`);
        }

        // remove old modertaor as member

        //@ts-ignore
        if (group.moderatorSettings.moderator) {
          //@ts-ignore
          group.groupMembers = group.groupMembers.filter((member: any) => group.moderatorSettings.moderator.toString() !== member.member.toString());
        }
        const setModeratorSettings: Record<string, any> = Object.keys(request.body.moderatorSettings).reduce((setModeratorSettings: Record<string, any>, param: string) => {
          setModeratorSettings[param] = request.body.moderatorSettings[param];
          return setModeratorSettings;
        }, {});

        //@ts-ignore
        if (moderatorUser._id === adminId) {
          //@ts-ignore
          avatarUploaded ? await fileUtils.autoUnlink(avatarUploaded.replace(process.env.APP_DOMAIN, process.cwd())) : null;
          return ApiResponse.badRequest(request, response, `Group Owner can not be a moderator.`);
        }
        setModeratorSettings.moderator = new mongoose.Types.ObjectId(setModeratorSettings.moderator);
        set.moderatorSettings = setModeratorSettings;
        //if new moderator is already a member
        const newModeratorAlreadyAMember = group.groupMembers.filter((member: any) => setModeratorSettings.moderator.toString() === member.member.toString())[0];
        if (newModeratorAlreadyAMember) {
          group.groupMembers = group.groupMembers.filter((member: any) => setModeratorSettings.moderator.toString() !== member.member.toString());
        }
        // add new moderator as member
        group.groupMembers.push({ member: setModeratorSettings.moderator, canPost: setModeratorSettings.canCreatePost, canComment: true });
        set.groupMembers = group.groupMembers;
        group = await GroupModel.findByIdAndUpdate({ _id: group._id }, { $set: set }, { new: true, upsert: false }).populate('moderatorSettings.moderator');
      }

      return ApiResponse.ok(request, response, { group, message: 'Group updated successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deleteGroup(request: Request, response: Response): Promise<any> {
    try {
      const { groupId } = request.body;
      const group: IGroupDoc | null = await GroupModel.findById(groupId);

      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      //@ts-ignore
      const adminId = request.user._id;
      const groupOwnerId = group.groupOwners.toString();
      //@ts-ignore
      if (adminId !== groupOwnerId) {
        return ApiResponse.badRequest(request, response, `Only group owner can delete the group.`);
      }
      const postIds = group.groupPosts;
      if (group.avatar) {
        //@ts-ignore
        fileUtils.autoUnlink(group.avatar.replace(process.env.APP_DOMAIN, process.cwd()));
      }
      const commentIdsToDelete: mongoose.Types.ObjectId[] = [];
      for (const postId of postIds) {
        const post: IPostDoc | null = await PostModel.findById(postId);
        if (post) {
          // const { groupId } = request.body;
          const group: IGroupDoc | null = await GroupModel.findById(groupId);

          if (!group) {
            return ApiResponse.badRequest(request, response, `Group not found.`);
          }
          //@ts-ignore
          if ((request.user._id === group.groupOwners.toString()) === false) {
            return ApiResponse.badRequest(request, response, `Only group owner can delete the group.`);
          }
          const postIds = group.groupPosts;
          if (group.avatar) {
            //@ts-ignore
            fileUtils.autoUnlink(group.avatar.replace(process.env.APP_DOMAIN, process.cwd()));
          }
          const commentIdsToDelete: mongoose.Types.ObjectId[] = [];
          for (const postId of postIds) {
            const post: IPostDoc | null = await PostModel.findById(postId).populate({ path: 'media' });
            if (post) {
              if (post.media) {
                //@ts-ignore
                await new S3Util().deleteFromS3Bucket(post.media.fileKey);
                await FileModel.findOneAndDelete({ _id: post.media._id });
              }
              commentIdsToDelete.push(...post.comments);
            }
          }
          await CommentModel.deleteMany({ _id: { $in: commentIdsToDelete } });
          await PostModel.deleteMany({ _id: { $in: postIds } });
          await GroupModel.findByIdAndDelete(groupId);
          return ApiResponse.ok(request, response, { groupId, message: 'Group deleted successfully.' });
        }
      }
      await CommentModel.deleteMany({ _id: { $in: commentIdsToDelete } });
      await PostModel.deleteMany({ _id: { $in: postIds } });
      await GroupModel.findByIdAndDelete(groupId);
      return ApiResponse.ok(request, response, { groupId, message: 'Group deleted successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async addMember(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, memberId, canPost, canComment } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      //@ts-ignore
      const adminId = request.user._id;
      const groupOwnerId = group.groupOwners.toString();
      if (adminId !== groupOwnerId) {
        return ApiResponse.badRequest(request, response, `Only group owner can delete the group.`);
      }
      const member: IUserDoc | null = await UserModel.findOne({ _id: memberId, role: 'user', isAdminApproved: true });
      if (!member) {
        return ApiResponse.badRequest(request, response, `Member not found.`);
      }
      const memberAlreadyExist = group.groupMembers.filter((member: any) => member.member.toString() === memberId)[0];
      if (memberAlreadyExist) {
        return ApiResponse.badRequest(request, response, `Member already exists in the group`);
      }
      if (group.isModerated) {
        if (group.moderatorSettings.moderator.toString() === memberId) {
          return ApiResponse.badRequest(request, response, `Member is group moderator and by default a member.`);
        }
      }
      group = await GroupModel.findByIdAndUpdate({ _id: groupId }, { $push: { groupMembers: { member: member._id, canPost, canComment } } }, { new: true, upsert: false });
      group = await GroupModel.findById(groupId)
        .populate({
          path: 'moderatorSettings.moderator',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'groupOwners',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'groupMembers.member',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'groupPosts',
          populate: [
            {
              path: 'media',
            },
          ],
        });

      return ApiResponse.ok(request, response, { group, message: 'Member added to group successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async updateMemberPermissions(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, memberId, canPost, canComment } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(groupId).populate({
        path: 'groupMembers.member',
        select: '_id name email avatar phoneNo role country state',
      });
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      //@ts-ignore
      const adminId = request.user._id;
      const groupOwnerId = group.groupOwners.toString();
      if (adminId !== groupOwnerId) {
        return ApiResponse.badRequest(request, response, `Only group owner can update member permissions.`);
      }
      const member: IUserDoc | null = await UserModel.findOne({ _id: memberId, role: 'user', isAdminApproved: true });
      if (!member) {
        return ApiResponse.badRequest(request, response, `Member is not a user.`);
      }
      const memberExistsInGroup: any = group.groupMembers.filter((member: any) => member.member._id.toString() === memberId)[0];
      if (!memberExistsInGroup) {
        return ApiResponse.badRequest(request, response, `Is not a group member.`);
      }
      if (group.isModerated) {
        //@ts-ignore
        if (memberExistsInGroup.member._id.toString() === group.moderatorSettings.moderator.toString()) {
          return ApiResponse.badRequest(request, response, `Moderator permissions can not be changed.`);
        }
      }

      group.groupMembers = group.groupMembers = group.groupMembers.map((member: any) => {
        if (member.member._id.toString() === memberId) {
          memberExistsInGroup.canPost = canPost;
          memberExistsInGroup.canComment = canComment;
          return memberExistsInGroup;
        } else {
          return member;
        }
      });
      await group.save();
      return ApiResponse.ok(request, response, { member: memberExistsInGroup, message: 'Member permissions updated successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async removeSingleMemberFromGroup(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, memberId } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      //@ts-ignore
      const adminId = request.user._id;
      const groupOwnerId = group.groupOwners.toString();
      if (adminId !== groupOwnerId) {
        return ApiResponse.badRequest(request, response, `Only group admin & moderator can remove member from group.`);
      }

      const member: IUserDoc | null = await UserModel.findOne({ _id: memberId, role: 'user', isAdminApproved: true });
      if (!member) {
        return ApiResponse.badRequest(request, response, `Member not found.`);
      }

      const memberAlreadyExist = group.groupMembers.filter((member: any) => member.member.toString() === memberId)[0];
      if (!memberAlreadyExist) {
        return ApiResponse.badRequest(request, response, `Member does not exists in the group`);
      }

      if (group.isModerated) {
        if (group.moderatorSettings.moderator.toString() === memberId) {
          return ApiResponse.badRequest(request, response, `Member is modertaor and can not be removed.`);
        }
      }
      group = await GroupModel.findByIdAndUpdate(
        { _id: groupId },
        {
          $pull: {
            groupMembers: {
              member: member._id,
            },
          },
        },
        { new: true, upsert: false }
      );
      /**
       *   groupMembers: {
              member: { $in: membersToRemove.map((memberId) => new mongoose.Types.ObjectId(memberId)) },
            },
       */
      group = await GroupModel.findById(groupId)
        .select('_id name avatar description isModerated groupMembers moderatorSettings groupOwners updatedAt createdAt')
        .populate({
          path: 'groupMembers.member',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'moderatorSettings.moderator',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'groupOwners',
          select: '_id name email avatar phoneNo role country state',
        });

      return ApiResponse.ok(request, response, { group, message: 'Member removed from group successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async addBulkMembers(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, members } = request.body;

      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, 'Group not found.');
      }
      //@ts-ignore
      const adminId = request.user._id;
      const groupOwnerId = group.groupOwners.toString();
      if (adminId !== groupOwnerId) {
        return ApiResponse.badRequest(request, response, `Only group owner or moderator can modify members.`);
      }
      const newMembers = [];
      for (const providedMember of members) {
        const member: IUserDoc | null = await UserModel.findOne({ _id: providedMember.memberId, role: 'user', isAdminApproved: true });
        if (!member) {
          return ApiResponse.badRequest(request, response, `Member with id ${providedMember.memberId} not found.`);
        }

        const isMemberAlreadyExist = group.groupMembers.some((m) => m.member.toString() === providedMember.memberId);
        if (!isMemberAlreadyExist) {
          newMembers.push({ canComment: providedMember.canComment, canPost: providedMember.canPost, member: providedMember.memberId });
        }
      }
      if (newMembers.length === 0) {
        return ApiResponse.ok(request, response, { message: 'All groupMembers are already part of the group.' });
      }
      group = await GroupModel.findByIdAndUpdate({ _id: groupId }, { $push: { groupMembers: { $each: newMembers } } }, { new: true, upsert: false });
      group = await GroupModel.findById(groupId)
        .select('_id name avatar description isModerated groupMembers moderatorSettings groupOwners updatedAt createdAt')
        .populate({
          path: 'groupMembers.member',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'moderatorSettings.moderator',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'groupOwners',
          select: '_id name email avatar phoneNo role country state',
        });

      return ApiResponse.ok(request, response, { group: group, message: 'Members added to group successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async removeBulkMembers(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, memberIds } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, 'Group not found.');
      }
      //@ts-ignore
      const adminId = request.user._id;
      const groupOwnerId = group.groupOwners.toString();
      if (adminId !== groupOwnerId) {
        return ApiResponse.badRequest(request, response, `Only group owner can delete the group.`);
      }
      const membersToRemove = [];
      for (const memberId of memberIds) {
        const isMemberExist = group.groupMembers.filter((m: any) => m.member.toString() === memberId)[0];
        if (isMemberExist) {
          membersToRemove.push(memberId);
        }
      }

      if (membersToRemove.length === 0) {
        return ApiResponse.ok(request, response, { message: 'No groupMembers to remove from the group.' });
      }

      group = await GroupModel.findByIdAndUpdate(
        { _id: groupId },
        {
          $pull: {
            groupMembers: {
              member: { $in: membersToRemove.map((memberId) => new mongoose.Types.ObjectId(memberId)) },
            },
          },
        },
        { new: true, upsert: false }
      );

      group = await GroupModel.findById(groupId)
        .select('_id name avatar description isModerated groupMembers moderatorSettings groupOwners updatedAt createdAt')
        .populate({
          path: 'groupMembers.member',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'moderatorSettings.moderator',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({
          path: 'groupOwners',
          select: '_id name email avatar phoneNo role country state',
        });

      return ApiResponse.ok(request, response, { group, message: 'Members removed from group successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async approveGroupPost(request: Request, response: Response): Promise<any> {
    try {
      const { postId } = request.body;
      const post: IPostDoc | null = await PostModel.findById(postId).populate('group postCreator');
      if (!post) {
        return ApiResponse.badRequest(request, response, 'Post not found.');
      }
      if (post.isGroupAdminApproved) {
        return ApiResponse.badRequest(request, response, 'Post is already approved.');
      }
      // const group: IGroupDoc = await GroupModel.findById(post.group).populate('groupOwners moderatorSettings.moderator');
      //@ts-ignore
      if (post.group.groupOwners.toString() !== request.user._id) {
        return ApiResponse.badRequest(request, response, `Only group owner can approve the post`);
      }
      await PostModel.findByIdAndUpdate({ _id: postId }, { $set: { isGroupAdminApproved: true } });

      return ApiResponse.ok(request, response, { post, message: 'Post approved successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async getGroupPosts(request: Request, response: Response): Promise<any> {
    try {
      const resultPerPage = 50;
      const apiFeature = new ApiFeature(
        PostModel.find({})
          .populate({
            path: 'postCreator',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'likes',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'comments',
            populate: [{ path: 'user', model: 'User', select: '_id name email avatar phoneNo role country state' }],
          })
          .populate({
            path: 'media',
          }),
        request.query
      )
        .search()
        .filter()
        .pagination(resultPerPage);
      let posts: IPostDoc[] = await apiFeature.query;
      posts = await Promise.all(
        posts.map(async (post: any) => {
          if (post.media) {
            post.media.s3PublicUrl = await new S3Util().getSignedUrl(post.media.fileKey, post.media.s3PublicUrl);
            return post;
          } else {
            return post;
          }
        })
      );
      return ApiResponse.ok(request, response, { posts, message: 'Success.' });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async updateGroupMemberPermissions(request: Request, response: Response, next: NextFunction) {
    const { groupId } = request.body;
    return ApiResponse.ok(request, response, `Group permissions updated successfully.`);
  },
};

export default adminGroupController;
