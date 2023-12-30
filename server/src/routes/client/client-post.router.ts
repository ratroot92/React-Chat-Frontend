import express from 'express';
import clientPostController from '../../controller/client/post.controller';
import { ProtectClient } from '../../passport';

const clientPostRouter = express.Router();
// const routerPrefix = '/user/post';
clientPostRouter.post(`/`, ProtectClient, clientPostController.createPost);
clientPostRouter.delete(`/`, ProtectClient, clientPostController.deletePost);
clientPostRouter.get(`/`, ProtectClient, clientPostController.getPosts);
clientPostRouter.post(`/like`, ProtectClient, clientPostController.likePost);
clientPostRouter.post(`/unlike`, ProtectClient, clientPostController.unlikePost);
clientPostRouter.post(`/comment`, ProtectClient, clientPostController.postComment);
clientPostRouter.delete(`/comment`, ProtectClient, clientPostController.deleteComment);
clientPostRouter.post(`/comment/like`, ProtectClient, clientPostController.likeComment);
export default clientPostRouter;
