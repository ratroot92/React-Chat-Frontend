import express from 'express';
import adminClientController from '../../controller/admin/client.controller';
import { ApiResponse } from '../../helpers';
import { ProtectAdmin } from '../../passport';

const adminClientRouter = express.Router();
// // const routerPrefix = '/admin/client';
adminClientRouter.get(`/`, ProtectAdmin, adminClientController.getAllClients);
adminClientRouter.delete(`/:id`, ProtectAdmin, adminClientController.deleteClient);
adminClientRouter.post(`/:id/approve`, ProtectAdmin, ApiResponse.requireParamId, adminClientController.approve);
adminClientRouter.post(`/:id/deny`, ProtectAdmin, ApiResponse.requireParamId, adminClientController.deny);
adminClientRouter.patch(`/`, ProtectAdmin, adminClientController.updateClientDownloadAccess);
adminClientRouter.get(`/content`, ProtectAdmin, adminClientController.getClientContent);
export default adminClientRouter;
