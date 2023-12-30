import express from 'express';
import clientUserController from '../../controller/client/user.controller';
import { ensureDir } from '../../helpers';
import { ProtectClient } from '../../passport';

const clientUserRouter = express.Router();
// const routerPrefix = '/user';
clientUserRouter.post('/seed', clientUserController.seed);
clientUserRouter.get('/clients', ProtectClient, clientUserController.getClients);
clientUserRouter.post('/', ProtectClient, clientUserController.create);
clientUserRouter.post('/password', clientUserController.forgotPassword);
clientUserRouter.post('/verify-otp', clientUserController.verifyOtp);
clientUserRouter.post('/password/reset', clientUserController.resetPassword);
clientUserRouter.get('/content', ProtectClient, clientUserController.getClientContent);
clientUserRouter.put('/avatar', ProtectClient, ensureDir(`${process.cwd()}/public/user/avatar`), clientUserController.upload, clientUserController.updateUserAvatar);
clientUserRouter.put('/', ProtectClient, clientUserController.updateUserPartial);
clientUserRouter.get('/groups', ProtectClient, clientUserController.getClientGroups);
export default clientUserRouter;
