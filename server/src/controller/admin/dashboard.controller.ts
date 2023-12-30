/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Router, Request, Response, NextFunction } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import { IUserDoc, UserModel } from '../../models/User';
import { ProtectAdmin } from '../../passport';
import { emailManager, ENUM_EMAIL_TEMPLATES, IEmailTemplateData, IMailOptions } from '../../helpers/email-manager';
import { CohortModel, ICohortDoc, IModuleDoc } from '../../models/Cohort';
import { ModuleAssignmentModel } from '../../models/ModuleAssigment';
import { PostModel } from '../../models/Post';
import { GroupModel } from '../../models/Group';
import { CommentModel } from '../../models/Comment';

const adminDashboardController = {
  async getDashboardStats(request: Request, response: Response): Promise<any> {
    try {
      const approvedUsers = await UserModel.find({ role: 'user', isAdminApproved: true }).count();
      const unApprovedUsers = await UserModel.find({ role: 'user', isAdminApproved: false }).count();
      const posts = await PostModel.count();
      const groups = await GroupModel.count();
      const comments = await CommentModel.count();

      const dashboardStats: any = await CohortModel.aggregate([
        {
          $unwind: '$modules',
        },
        {
          $unwind: '$modules.days',
        },
        {
          $group: {
            _id: null,
            totalDocs: { $sum: { $size: '$modules.days.docs' } },
            totalModules: { $sum: 1 },
            totalDays: { $sum: 1 },
            totalPdfs: { $sum: { $size: '$modules.days.pdfs' } },
            totalPpts: { $sum: { $size: '$modules.days.ppts' } },
            totalVideos: { $sum: { $size: '$modules.days.videos' } },
          },
        },
        {
          $project: {
            _id: 0,
          },
        },
      ]);
      dashboardStats[0].approvedUsers = approvedUsers;
      dashboardStats[0].unApprovedUsers = unApprovedUsers;
      dashboardStats[0].posts = posts;
      dashboardStats[0].groups = groups;

      return ApiResponse.created(request, response, { data: dashboardStats[0], message: 'success' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default adminDashboardController;
