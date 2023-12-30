/* eslint-disable security/detect-object-injection */
/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Router, Request, Response } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import { ProtectClient } from '../../passport';
import { GroupModel, IGroupDoc } from '../../models/Group';
import { IUserDoc, UserModel } from '../../models/User';
import mongoose from 'mongoose';

const clientGroupController = {
  async putGroup(request: Request, response: Response): Promise<any> {
    try {
      const { name } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(request.body._id).populate({
        path: 'moderatorSettings.moderator',
        select: '_id name email avatar phoneNo role country state',
      });
      if (!group) {
        return ApiResponse.notFound(request, response, `Group not found.`);
      }
      if (group.isModerated === false) {
        return ApiResponse.badRequest(request, response, `Group is not moderated.`);
      }
      if (!group.moderatorSettings.moderator) {
        return ApiResponse.badRequest(request, response, `Moderator not found.`);
      }

      //@ts-ignore
      const clientId = request.user._id;
      //@ts-ignore
      const groupModeratorId = group.moderatorSettings.moderator._id.toString();
      if (clientId !== groupModeratorId) {
        return ApiResponse.badRequest(request, response, `Only moderator is allowed to edit group details.`);
      }

      const UPDATEABLES = ['name', 'description'];

      const set: Record<string, any> = Object.keys(request.body).reduce((set: Record<string, any>, param: string) => {
        if (UPDATEABLES.includes(param)) {
          set[param] = request.body[param];
        }
        return set;
      }, {});
      const groupExistWithOldName: IGroupDoc | null = await GroupModel.findOne({ name });
      if (groupExistWithOldName) {
        if (groupExistWithOldName._id.toString() !== group._id.toString()) {
          return ApiResponse.badRequest(request, response, `Duplicate group 1.`);
        }
      }

      const groupExistWithNewName = await GroupModel.findOne({ name: set.name });
      if (groupExistWithNewName) {
        if (groupExistWithNewName._id.toString() !== group._id.toString()) {
          return ApiResponse.badRequest(request, response, `Duplicate group 2.`);
        }
      }
      group = await GroupModel.findByIdAndUpdate({ _id: group._id }, { $set: set }, { new: true, upsert: false }).populate('moderatorSettings.moderator');
      return ApiResponse.ok(request, response, { group, message: 'Group updated successfully.' });
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
        return ApiResponse.notFound(request, response, `Group not found.`);
      }
      if (group.isModerated === false) {
        return ApiResponse.badRequest(request, response, `Group is not moderated.`);
      }
      if (!group.moderatorSettings.moderator) {
        return ApiResponse.badRequest(request, response, `Moderator not found.`);
      }
      //@ts-ignore
      const clientId = request.user._id;
      //@ts-ignore
      const groupModeratorId = group.moderatorSettings.moderator._id.toString();
      if (memberId == clientId) {
        return ApiResponse.badRequest(request, response, `Moderator cant change its own permissions.`);
      }
      if (clientId !== groupModeratorId) {
        return ApiResponse.badRequest(request, response, `Only moderator is allowed to change group members permissions.`);
      }
      const member: IUserDoc | null = await UserModel.findOne({ _id: memberId, role: 'user', isAdminApproved: true });
      if (!member) {
        return ApiResponse.notFound(request, response, `Member is not a user.`);
      }
      const memberExistsInGroup: any = group.groupMembers.filter((member: any) => member.member._id.toString() === memberId)[0];
      if (!memberExistsInGroup) {
        return ApiResponse.notFound(request, response, `Is not a group member.`);
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

  async addBulkMembers(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, members } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.notFound(request, response, `Group not found.`);
      }
      if (group.isModerated === false) {
        return ApiResponse.badRequest(request, response, `Group is not moderated.`);
      }
      if (!group.moderatorSettings.moderator) {
        return ApiResponse.badRequest(request, response, `Moderator not found.`);
      }
      //@ts-ignore
      const clientId = request.user._id;
      //@ts-ignore
      const groupModeratorId = group.moderatorSettings.moderator._id.toString();
      if (clientId !== groupModeratorId) {
        return ApiResponse.badRequest(request, response, `Only moderator is allowed to add group members.`);
      }

      const newMembers = [];
      for (const providedMember of members) {
        const member: IUserDoc | null = await UserModel.findOne({ _id: providedMember.memberId, role: 'user', isAdminApproved: true });
        if (!member) {
          return ApiResponse.notFound(request, response, `Member with id ${providedMember.memberId} not found.`);
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

  async removeSingleMemberFromGroup(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, memberId } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.notFound(request, response, `Group not found.`);
      }
      if (group.isModerated === false) {
        return ApiResponse.badRequest(request, response, `Group is not moderated.`);
      }
      if (!group.moderatorSettings.moderator) {
        return ApiResponse.badRequest(request, response, `Moderator not found.`);
      }
      //@ts-ignore
      const clientId = request.user._id;
      //@ts-ignore
      const groupModeratorId = group.moderatorSettings.moderator._id.toString();
      if (clientId !== groupModeratorId) {
        return ApiResponse.badRequest(request, response, `Only moderator is allowed to add group members.`);
      }

      const member: IUserDoc | null = await UserModel.findOne({ _id: memberId, role: 'user', isAdminApproved: true });
      if (!member) {
        return ApiResponse.notFound(request, response, `Member not found.`);
      }

      const memberAlreadyExist = group.groupMembers.filter((member: any) => member.member.toString() === memberId)[0];
      if (!memberAlreadyExist) {
        return ApiResponse.badRequest(request, response, `Member does not exists in the group`);
      }

      if (group.moderatorSettings.moderator.toString() === memberId) {
        return ApiResponse.badRequest(request, response, `Member is modertaor and can not be removed.`);
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
  async removeBulkMembers(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, memberIds } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.notFound(request, response, `Group not found.`);
      }
      if (group.isModerated === false) {
        return ApiResponse.badRequest(request, response, `Group is not moderated.`);
      }
      if (!group.moderatorSettings.moderator) {
        return ApiResponse.badRequest(request, response, `Moderator not found.`);
      }
      //@ts-ignore
      const clientId = request.user._id;
      //@ts-ignore
      const groupModeratorId = group.moderatorSettings.moderator._id.toString();
      if (clientId !== groupModeratorId) {
        return ApiResponse.badRequest(request, response, `Only moderator is allowed to remove group members.`);
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
};

export default clientGroupController;
