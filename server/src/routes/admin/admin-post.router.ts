import express from 'express';
import adminPostController from '../../controller/admin/post.controller';
import { ProtectAdmin } from '../../passport';

const adminPostRouter = express.Router();
// const routerPrefix = '/admin/post';
adminPostRouter.post(`/`, ProtectAdmin, adminPostController.createGroupPost);
adminPostRouter.delete(`/`, ProtectAdmin, adminPostController.deleteGroupPost);
adminPostRouter.post(`/comment`, ProtectAdmin, adminPostController.createCommentOnPost);
adminPostRouter.delete(`/comment`, ProtectAdmin, adminPostController.deletePostComment);
adminPostRouter.post(`/like`, ProtectAdmin, adminPostController.likePost);
adminPostRouter.post(`/unlike`, ProtectAdmin, adminPostController.unlikePost);

export default adminPostRouter;
