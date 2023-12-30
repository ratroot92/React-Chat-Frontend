import express from 'express';
import adminAuthController from '../../controller/admin/auth.controller';
import { ProtectAdmin } from '../../passport';

const adminAuthRouter = express.Router();
// // const routerPrefix = '/admin/auth';
adminAuthRouter.post(`/login`, adminAuthController.login);
adminAuthRouter.post(`/logout`, ProtectAdmin, adminAuthController.logout);
adminAuthRouter.get(`/is-authenticated`, ProtectAdmin, adminAuthController.isAuthenticated);
adminAuthRouter.post(`/register`, adminAuthController.register);
export default adminAuthRouter;
