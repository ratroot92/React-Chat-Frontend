import express from 'express';
import adminSessionController from '../../controller/admin/session.controller';
import { ProtectAdmin } from '../../passport';

const adminSessionRouter = express.Router();
// const routerPrefix = '/admin/session';
adminSessionRouter.post(`/`, ProtectAdmin, adminSessionController.createSession);
adminSessionRouter.get(`/`, ProtectAdmin, adminSessionController.getAllSessions);
adminSessionRouter.delete(`/`, ProtectAdmin, adminSessionController.deleteSessionById);
adminSessionRouter.put(`/`, ProtectAdmin, adminSessionController.updateSessionById);
export default adminSessionRouter;
