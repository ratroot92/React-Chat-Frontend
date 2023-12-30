import express, { Request } from 'express';
import multer from 'multer';
import path from 'path';
import adminUserController from '../../controller/admin/admin.controller';
import { ensureDir } from '../../helpers';
import { ProtectAdmin } from '../../passport';

const avatarStorage = multer.diskStorage({
  // @ts-ignore
  destination(request: Request, file: any, cb: any) {
    return cb(null, `${process.cwd()}/public/client/avatar`);
  },
  filename(request: Request, file: any, cb: any) {
    return cb(null, Date.now() + path.extname(file.originalname)); // Appending extension
  },
});
// @ts-ignore
const upload = multer({ storage: avatarStorage }).single('avatar');
const adminUserRouter = express.Router();
// const routerPrefix = '/admin';

adminUserRouter.get(`/`, ProtectAdmin, adminUserController.getAllAdmins);
// Unprotected Routes
adminUserRouter.post(`/password`, adminUserController.forgotPassword);
adminUserRouter.post(`/verify-otp`, adminUserController.verifyOtp);
adminUserRouter.post(`/password/reset`, adminUserController.resetPassword);
adminUserRouter.put(`/avatar`, ProtectAdmin, ensureDir(`${process.cwd()}/public/client/avatar`), upload, adminUserController.updateUserAvatar);
adminUserRouter.put(`/`, ProtectAdmin, adminUserController.updateUserPartial);
export default adminUserRouter;
