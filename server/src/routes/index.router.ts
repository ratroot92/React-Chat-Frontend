import express from 'express';
import adminAuthRouter from './admin/admin-auth.router';
import adminClientRouter from './admin/admin-client.router';
import adminCohortRouter from './admin/admin-cohort.router';
import adminDashboardRouter from './admin/admin-dashboard.router';
import adminGroupRouter from './admin/admin-group.router';
import adminModuleAssignmentRouter from './admin/admin-moduleAssignment.router';
import adminPostRouter from './admin/admin-post.router';
import adminSessionRouter from './admin/admin-session.router';
import adminUserRouter from './admin/admin-user.router';
import chatRouter from './chat.router';
import clientAuthRouter from './client/client-auth.router';
import clientGroupRouter from './client/client-group.router';
import clientPostRouter from './client/client-post.router';
import clientUserRouter from './client/client-user.router';
import s3Router from './s3.router';
import scriptRouter from './script.router';
const appRouter = express.Router();

// Admin
appRouter.use(`/admin/auth`, adminAuthRouter);
appRouter.use(`/admin/client`, adminClientRouter);
appRouter.use(`/admin/cohort`, adminCohortRouter);
appRouter.use(`/admin/dashboard`, adminDashboardRouter);
appRouter.use(`/admin/group`, adminGroupRouter);
appRouter.use(`/admin/assignment`, adminModuleAssignmentRouter);
appRouter.use(`/admin/post`, adminPostRouter);
appRouter.use(`/admin/session`, adminSessionRouter);
appRouter.use(`/admin`, adminUserRouter);

// Client
appRouter.use(`/user`, clientUserRouter);
appRouter.use(`/user/group`, clientGroupRouter);
appRouter.use(`/user/post`, clientPostRouter);
appRouter.use(`/auth`, clientAuthRouter);

// Misc
appRouter.use('/s3', s3Router);
appRouter.use('/chat', chatRouter);
appRouter.use('/script', scriptRouter);

export default appRouter;
