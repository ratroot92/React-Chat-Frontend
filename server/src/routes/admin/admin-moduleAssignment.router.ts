import express from 'express';
import adminModuleAssigmentController from '../../controller/admin/moduleAssignment.controller';
import { ProtectAdmin } from '../../passport';

const adminModuleAssignmentRouter = express.Router();
// const routerPrefix = '/admin/assignment';
adminModuleAssignmentRouter.post(`/`, ProtectAdmin, adminModuleAssigmentController.createModuleAssignment);
adminModuleAssignmentRouter.get(`/`, ProtectAdmin, adminModuleAssigmentController.getAllModuleAssignments);
adminModuleAssignmentRouter.get(`/user/:id`, ProtectAdmin, adminModuleAssigmentController.getAllModuleAssignments);

adminModuleAssignmentRouter.delete(`/`, ProtectAdmin, adminModuleAssigmentController.deleteAllModuleAssignments);

adminModuleAssignmentRouter.patch(`/`, ProtectAdmin, adminModuleAssigmentController.updateModuleAssignmentById);
export default adminModuleAssignmentRouter;
