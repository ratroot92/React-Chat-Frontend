/* eslint-disable security/detect-object-injection */
/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Router, Request, Response } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import { ProtectAdmin } from '../../passport';
import { IPostDoc, PostModel } from '../../models/Post';
import { CommentModel, ICommentDoc } from '../../models/Comment';
import { GroupModel, IGroupDoc } from '../../models/Group';
import mongoose from 'mongoose';
import { FileModel, IS3FileDoc } from '../../models/File';
import S3Util from '../../utils/s3';

const adminPostController = {
  async createGroupPost(request: Request, response: Response): Promise<any> {
    try {
      const { content, groupId, title, mediaId } = request.body;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      // @ts-ignore
      const adminId = request.user._id;
      // @ts-ignore
      const isGroupOwner = adminId === group.groupOwners.toString() ? true : false;
      if (!isGroupOwner) {
        return ApiResponse.badRequest(request, response, `Only group owner can publish a post.`);
      }
      let media: IS3FileDoc | null = null;
      if (mediaId) {
        media = await FileModel.findById(mediaId);
        if (!media) {
          return ApiResponse.badRequest(request, response, `Media not found.`);
        }
      }

      let post: IPostDoc | null = new PostModel({
        group: group._id,
        title,
        content: content ? content : '',
        // @ts-ignore
        postCreator: adminId,
        isGroupAdminApproved: true,
        media: media ? media._id : null,
      });
      await post.save();
      group = await GroupModel.findByIdAndUpdate({ _id: group._id }, { $push: { groupPosts: post._id } }, { new: true, upsert: false });
      post = await PostModel.findById(post._id)
        .populate({
          path: 'postCreator',
          select: '_id name email avatar phoneNo role country state',
        })
        .populate({ path: 'media' });
      return ApiResponse.ok(request, response, { post, message: `Post created successfully.` });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async deleteGroupPost(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, postId } = request.body;
      //@ts-ignore
      const adminId = request.user._id;
      const group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }

      const post: IPostDoc | null = await PostModel.findById(postId).populate('group');
      if (!post) {
        return ApiResponse.notFound(request, response, `Post not found.`);
      }
      //@ts-ignore

      //@ts-ignore
      if (adminId !== group.groupOwners.toString()) {
        return ApiResponse.badRequest(request, response, `Only group owner can delete post.`);
      }
      if (post.media) {
        const mediaFound: IS3FileDoc | null = await FileModel.findById(post.media);
        if (mediaFound) {
          const s3Util = new S3Util();
          await s3Util.deleteFromS3Bucket(mediaFound.fileKey);
          await FileModel.findByIdAndDelete(post.media);
        }
      }

      await CommentModel.deleteMany({ post: postId });
      await PostModel.deleteOne({ _id: postId });
      return ApiResponse.ok(request, response, { postId, message: 'Post deleted successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async createCommentOnPost(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, content, postId } = request.body;
      // @ts-ignore
      const adminId = request.user._id;
      // Group Exists
      const group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.notFound(request, response, `Group not found.`);
      }
      if (adminId !== group.groupOwners.toString()) {
        return ApiResponse.badRequest(request, response, `Only group owner publish a comment.`);
      }
      // Posts Exists
      let post: IPostDoc | null = await PostModel.findById(postId);
      if (!post) {
        return ApiResponse.notFound(request, response, `Post not found.`);
      }
      // Posts belongs to Group
      const postBelongsToGroup: mongoose.Types.ObjectId[] = group.groupPosts.filter((post: mongoose.Types.ObjectId) => post.toString() === postId);
      if (!postBelongsToGroup.length) {
        return ApiResponse.notFound(request, response, `Post does not exists in the group.`);
      }
      let comment: ICommentDoc = new CommentModel({ content, post: postId, user: adminId });
      comment = await comment.save();
      post = await PostModel.findByIdAndUpdate({ _id: postId }, { $push: { comments: comment._id } }, { new: true, upsert: false });
      return ApiResponse.ok(request, response, { comment, message: `Comment posted successfully.` });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async deletePostComment(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, postId, commentId } = request.body;

      const group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      //@ts-ignore
      const adminId = request.user._id;
      if (adminId !== group.groupOwners.toString()) {
        return ApiResponse.badRequest(request, response, `Only group owner can delete a comment.`);
      }

      const post: IPostDoc | null = await PostModel.findById(postId);
      if (!post || post.group.toString() !== groupId) {
        return ApiResponse.notFound(request, response, `Post not found.`);
      }

      const comment: ICommentDoc | null = await CommentModel.findById(commentId);
      if (!comment || comment.post.toString() !== postId) {
        return ApiResponse.notFound(request, response, `Comment not found.`);
      }

      await CommentModel.deleteOne({ _id: commentId });

      return ApiResponse.ok(request, response, { commentId, message: 'Comment deleted successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async likePost(request: Request, response: Response): Promise<any> {
    try {
      const { postId } = request.body;
      // @ts-ignore
      let post: IPostDoc | null = await PostModel.findById(postId).populate({ path: 'group' });
      if (!post) {
        return ApiResponse.badRequest(request, response, `Post not found.`);
      }
      //@ts-ignore
      const adminId = request.user._id;
      //@ts-ignore

      if (adminId !== post.group.groupOwners.toString()) {
        return ApiResponse.badRequest(request, response, `Only group owner can like group post.`);
      }

      //@ts-ignore
      const userAlreadyLikedPost = post.likes.filter((user) => user.toString() === adminId)[0];
      if (userAlreadyLikedPost) {
        return ApiResponse.badRequest(request, response, `User already liked the Post`);
      }
      //@ts-ignore
      post = await PostModel.findByIdAndUpdate({ _id: postId }, { $push: { likes: adminId } }, { new: true, upsert: false });
      post = await PostModel.findById(postId)
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
        });
      return ApiResponse.ok(request, response, { post, message: `Success.` });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async unlikePost(request: Request, response: Response): Promise<any> {
    try {
      const { postId } = request.body;
      // @ts-ignore
      let post: IPostDoc | null = await PostModel.findById(postId).populate({ path: 'group' });
      if (!post) {
        return ApiResponse.badRequest(request, response, `Post not found.`);
      }
      //@ts-ignore
      const adminId = request.user._id;
      //@ts-ignore

      if (adminId !== post.group.groupOwners.toString()) {
        return ApiResponse.badRequest(request, response, `Only group owner can unlike group post.`);
      }
      //@ts-ignore
      const isPostLikedbyUser = post.likes.filter((user) => user.toString() === adminId)[0];
      if (!isPostLikedbyUser) {
        return ApiResponse.badRequest(request, response, `Only liked post can be unliked.`);
      }
      //@ts-ignore
      post = await PostModel.findByIdAndUpdate({ _id: postId }, { $pull: { likes: adminId } }, { new: true, upsert: false });
      post = await PostModel.findById(postId)
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
        });
      return ApiResponse.ok(request, response, { post, message: `Success.` });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default adminPostController;
