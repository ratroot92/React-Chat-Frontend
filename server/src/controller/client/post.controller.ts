/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Router, Request, Response } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import { ProtectClient } from '../../passport';
import { IPostDoc, PostModel } from '../../models/Post';
import { GroupModel, IGroupDoc } from '../../models/Group';
import { CommentModel, ICommentDoc } from '../../models/Comment';
import ApiFeature from '../../helpers/api-feature';
import { FileModel, IS3FileDoc } from '../../models/File';
import S3Util from '../../utils/s3';

const clientPostController = {
  async createPost(request: Request, response: Response): Promise<any> {
    try {
      const { content, groupId, title, mediaId } = request.body;
      // @ts-ignore
      const clientId = request.user._id;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }

      // @ts-ignore
      const isUserAGroupMember = group.groupMembers.filter((member: any) => member.member.toString() === clientId)[0];
      if (!isUserAGroupMember) {
        return ApiResponse.badRequest(request, response, `User is not a member of group '${group.name}'.`);
      }
      if (!isUserAGroupMember.canPost) {
        return ApiResponse.badRequest(request, response, `Member can not post'.`);
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
        postCreator: clientId,
        media: media ? media._id : null,
      });
      await post.save();
      group = await GroupModel.findByIdAndUpdate({ _id: group._id }, { $push: { groupPosts: post._id } }, { new: true, upsert: false });
      post = await PostModel.findById(post._id).populate('group postCreator media');
      return ApiResponse.ok(request, response, { post, message: `Post created successfully.` });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deletePost(request: Request, response: Response): Promise<any> {
    try {
      const { groupId, postId } = request.body;
      // @ts-ignore
      const clientId = request.user._id;
      let group: IGroupDoc | null = await GroupModel.findById(groupId);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      const post: IPostDoc | null = await PostModel.findById(postId).populate({ path: 'media' });
      if (!post) {
        return ApiResponse.badRequest(request, response, `Post not found.`);
      }
      // @ts-ignore
      const isUserAGroupMember = group.groupMembers.filter((member: any) => member.member.toString() === clientId)[0];
      if (!isUserAGroupMember) {
        return ApiResponse.badRequest(request, response, `User is not a member of group '${group.name}'.`);
      }
      if (post.media) {
        //@ts-ignore
        await new S3Util().deleteFromS3Bucket(post.media.fileKey);
        await FileModel.findOneAndDelete({ _id: post.media._id });
      }
      group = await GroupModel.findByIdAndUpdate({ _id: group._id }, { $pull: { groupPosts: post._id } }, { new: true, upsert: false });
      await PostModel.findByIdAndDelete({ _id: post._id });
      return ApiResponse.ok(request, response, { postId, message: `Post deleted successfully.` });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async getPosts(request: Request, response: Response): Promise<any> {
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

  async likePost(request: Request, response: Response): Promise<any> {
    try {
      const { postId } = request.body;
      // @ts-ignore
      const clientId = request.user._id;
      let post: IPostDoc | null = await PostModel.findById(postId);
      if (!post) {
        return ApiResponse.badRequest(request, response, `Post not found.`);
      }
      //@ts-ignore
      const userAlreadyLikedPost = post.likes.filter((user) => user.toString() === clientId)[0];
      if (userAlreadyLikedPost) {
        return ApiResponse.badRequest(request, response, `User already liked the Post`);
      }
      //@ts-ignore
      post = await PostModel.findByIdAndUpdate({ _id: postId }, { $push: { likes: clientId } }, { new: true, upsert: false });
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
        })
        .populate('media');
      return ApiResponse.ok(request, response, { post, message: `Success.` });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async unlikePost(request: Request, response: Response): Promise<any> {
    try {
      const { postId } = request.body;
      // @ts-ignore
      const clientId = request.user._id;
      let post: IPostDoc | null = await PostModel.findById(postId);
      if (!post) {
        return ApiResponse.badRequest(request, response, `Post not found.`);
      }
      //@ts-ignore
      const isPostLikedbyUser = post.likes.filter((user) => user.toString() === clientId)[0];
      if (!isPostLikedbyUser) {
        return ApiResponse.badRequest(request, response, `Only liked post can be unliked.`);
      }
      //@ts-ignore
      post = await PostModel.findByIdAndUpdate({ _id: postId }, { $pull: { likes: clientId } }, { new: true, upsert: false });
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
        })
        .populate('media');
      return ApiResponse.ok(request, response, { post, message: `Success.` });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async postComment(request: Request, response: Response): Promise<any> {
    try {
      const { content, postId } = request.body;
      // @ts-ignore
      const clientId = request.user._id;
      let post: IPostDoc | null = await PostModel.findById(postId);
      if (!post) {
        return ApiResponse.badRequest(request, response, `Post not found.`);
      }
      const group: IGroupDoc | null = await GroupModel.findById(post.group);
      if (!group) {
        return ApiResponse.badRequest(request, response, `Group not found.`);
      }
      const isUserAGroupMember = group.groupMembers.filter((member: any) => member.member.toString() === clientId)[0];
      if (!isUserAGroupMember) {
        return ApiResponse.badRequest(request, response, `User is not a member of group '${group.name}'.`);
      }

      if (!isUserAGroupMember.canComment) {
        return ApiResponse.badRequest(request, response, `Member not allowed to comment.`);
      }
      let comment: ICommentDoc | null = new CommentModel({
        content,
        post: postId,
        user: clientId,
      });
      comment = await comment.save();
      post = await PostModel.findByIdAndUpdate({ _id: postId }, { $push: { comments: comment._id } }, { new: true, upsert: false });
      return ApiResponse.ok(request, response, { comment, message: `Comment posted successfully.` });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deleteComment(request: Request, response: Response): Promise<any> {
    try {
      const { postId, commentId } = request.body;
      let post: IPostDoc | null = await PostModel.findById(postId);
      if (!post) {
        return ApiResponse.notFound(request, response, `Post not found.`);
      }
      const doesCommentExists = post.comments.filter((comment: any) => comment.toString() === commentId)[0];
      if (!doesCommentExists) {
        return ApiResponse.notFound(request, response, `Comment not found.`);
      }
      post = await PostModel.findByIdAndUpdate({ _id: post._id }, { $pull: { comments: doesCommentExists._id } }, { new: true, upsert: false });
      return ApiResponse.ok(request, response, { data: commentId, message: `Comment deleted successfully.` });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async likeComment(request: Request, response: Response): Promise<any> {
    try {
      const { commentId } = request.body;
      // @ts-ignore
      const clientId = request.user._id;

      let comment: ICommentDoc | null = await CommentModel.findById(commentId);
      if (!comment) {
        return ApiResponse.badRequest(request, response, `Comment not found.`);
      }
      //@ts-ignore
      const userAlreadyLikedComment = comment.likes.filter((user) => user === clientId || user.toString() === clientId)[0];
      if (userAlreadyLikedComment) {
        return ApiResponse.badRequest(request, response, `User already liked the Comment`);
      }
      //@ts-ignore
      comment = await CommentModel.findByIdAndUpdate({ _id: commentId }, { $push: { likes: request.user._id } }, { new: true, upsert: false });
      comment = await CommentModel.findById(commentId).populate('user likes');
      return ApiResponse.ok(request, response, { comment, message: `Success.` });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default clientPostController;
