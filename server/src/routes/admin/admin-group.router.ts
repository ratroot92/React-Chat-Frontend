import express from 'express';
import adminGroupController from '../../controller/admin/group.controller';
import { ensureDir } from '../../helpers';
import { ProtectAdmin } from '../../passport';

const adminGroupRouter = express.Router();
// const routerPrefix = '/admin/group';
adminGroupRouter.post('/', ProtectAdmin, ensureDir(`${process.cwd()}/public/group/avatar`), adminGroupController.createGroup);
//
adminGroupRouter.post(`/:id/avatar`, ProtectAdmin, ensureDir(`${process.cwd()}/public/group/avatar`), adminGroupController.addGroupAvatar);
adminGroupRouter.get(`/`, ProtectAdmin, adminGroupController.getGroups);
adminGroupRouter.put(`/`, ProtectAdmin, adminGroupController.putGroup);
adminGroupRouter.delete(`/`, ProtectAdmin, adminGroupController.deleteGroup);
adminGroupRouter.post(`/member`, ProtectAdmin, adminGroupController.addMember);
adminGroupRouter.put(`/member`, ProtectAdmin, adminGroupController.updateMemberPermissions);

adminGroupRouter.delete(`/member`, ProtectAdmin, adminGroupController.removeSingleMemberFromGroup);
adminGroupRouter.post(`/member/bulk`, ProtectAdmin, adminGroupController.addBulkMembers);
adminGroupRouter.delete(`/member/bulk`, ProtectAdmin, adminGroupController.removeBulkMembers);
adminGroupRouter.post(`/post/approve`, ProtectAdmin, adminGroupController.approveGroupPost);
adminGroupRouter.get(`/post`, ProtectAdmin, adminGroupController.getGroupPosts);
adminGroupRouter.put(`/permissions`, ProtectAdmin, adminGroupController.updateGroupMemberPermissions);
export default adminGroupRouter;
