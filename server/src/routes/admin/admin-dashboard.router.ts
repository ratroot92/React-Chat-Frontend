import express from 'express';
import adminDashboardController from '../../controller/admin/dashboard.controller';
import { ProtectAdmin } from '../../passport';

const adminDashboardRouter = express.Router();
// const routerPrefix = '/admin/dashboard';
adminDashboardRouter.get(`/statistics`, ProtectAdmin, adminDashboardController.getDashboardStats);

export default adminDashboardRouter;
