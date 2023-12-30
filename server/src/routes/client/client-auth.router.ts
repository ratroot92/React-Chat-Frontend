import express from 'express';
import clientAuthController from '../../controller/client/auth.controller';
import { ProtectClient } from '../../passport';

const clientAuthRouter = express.Router();
// const routerPrefix = '/auth';
clientAuthRouter.post(`/login`, clientAuthController.login);
clientAuthRouter.post(`/logout`, ProtectClient, clientAuthController.logout);
clientAuthRouter.get(`/is-authenticated`, ProtectClient, clientAuthController.isAuthenticated);
clientAuthRouter.post(`/register`, clientAuthController.register);
export default clientAuthRouter;
