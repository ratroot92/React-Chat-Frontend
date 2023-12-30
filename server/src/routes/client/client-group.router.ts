import express from 'express';
import clientGroupController from '../../controller/client/group.controller';
import { ProtectClient } from '../../passport';

const clientGroupRouter = express.Router();
// const routerPrefix = '/user/group';
clientGroupRouter.put(`/`, ProtectClient, clientGroupController.putGroup);
clientGroupRouter.put(`/member`, ProtectClient, clientGroupController.updateMemberPermissions);
clientGroupRouter.post(`/member/bulk`, ProtectClient, clientGroupController.addBulkMembers);
clientGroupRouter.delete(`/member`, ProtectClient, clientGroupController.removeSingleMemberFromGroup);
clientGroupRouter.delete(`/member/bulk`, ProtectClient, clientGroupController.removeBulkMembers);
export default clientGroupRouter;
