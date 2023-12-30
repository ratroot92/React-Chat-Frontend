/* eslint-disable @typescript-eslint/ban-ts-comment */
import { NextFunction, Request, Response } from 'express';
import { ApiResponse } from '../../helpers/api';
import { ENUM_EMAIL_TEMPLATES, IEmailTemplateData, IMailOptions, emailManager } from '../../helpers/email-manager';
import { CohortModel, ICohortDoc, IModuleDoc } from '../../models/Cohort';
import { ModuleAssignmentModel } from '../../models/ModuleAssigment';
import { IUserDoc, UserModel } from '../../models/User';

const adminClientController = {
  async getAllClients(request: Request, response: Response): Promise<any> {
    try {
      if (request.query.id) {
        const user: IUserDoc | null = await UserModel.findOne({ _id: request.query.id, role: 'user' });
        if (!user) {
          return ApiResponse.notFound(request, response, `User not found.`);
        } else {
          return ApiResponse.ok(request, response, { user });
        }
      } else {
        let users: IUserDoc[] = await UserModel.find({ role: 'user' });
        users = await Promise.all(
          users.map(async (user) => {
            const userCohorts = await CohortModel.find({ 'modules.users': user._id }).select('name modules.users modules.name');
            const filteredCohorts = userCohorts.filter((cohort: ICohortDoc) => {
              cohort.modules = cohort.modules.filter((module: IModuleDoc) => module.users.length > 0 && module.users.includes(user._id.toString()));
              if (cohort.modules.length) {
                return cohort;
              }
              return;
            });
            const newUser = JSON.parse(JSON.stringify(user));
            newUser.cohorts = filteredCohorts;
            return newUser;
          })
        );
        return ApiResponse.created(request, response, { data: users, message: 'success' });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async approve(request: Request, response: Response): Promise<any> {
    try {
      if (request.params.id) {
        let user: IUserDoc | null = await UserModel.findOne({ _id: request.params.id, role: 'user' });
        if (!user) {
          return ApiResponse.notFound(request, response);
        } else {
          user = await UserModel.findByIdAndUpdate({ _id: user._id }, { isAdminApproved: true }, { new: true, upsert: false });
          if (!user) {
            return ApiResponse.badRequest(request, response, 'Failed to approve client.');
          } else {
            const templateOptons: IEmailTemplateData = {
              // @ts-ignore
              recipient: user.email,
              template: ENUM_EMAIL_TEMPLATES.CLIENT_APPROVED,
              info: { user },
            };
            const mailOptions: IMailOptions = emailManager.getEmailTemplate(templateOptons);
            // await emailManager.send(mailOptions);
            return ApiResponse.ok(request, response, { user });
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deny(request: Request, response: Response): Promise<any> {
    try {
      if (request.params.id) {
        let user: IUserDoc | null = await UserModel.findOne({ _id: request.params.id, role: 'user' });
        if (!user) {
          return ApiResponse.notFound(request, response);
        } else {
          user = await UserModel.findByIdAndUpdate({ _id: user._id }, { isAdminApproved: false }, { new: true, upsert: false });
          if (!user) {
            return ApiResponse.badRequest(request, response, 'Failed to disApprove client.');
          } else {
            const templateOptons: IEmailTemplateData = {
              recipient: user.email,
              template: ENUM_EMAIL_TEMPLATES.CLIENT_DISAPPROVED,
              info: { user },
            };
            const mailOptions: IMailOptions = emailManager.getEmailTemplate(templateOptons);
            // await emailManager.send(mailOptions);
            return ApiResponse.ok(request, response, { user });
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async deleteClient(request: Request, response: Response): Promise<any> {
    try {
      if (request.params.id) {
        let user: IUserDoc | null = await UserModel.findOne({ _id: request.params.id, role: 'user' });
        if (!user) {
          return ApiResponse.notFound(request, response);
        } else {
          user = await UserModel.findByIdAndDelete({ _id: user._id });
          return ApiResponse.ok(request, response, {});
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async getAll(request: Request, response: Response): Promise<any> {
    try {
      const users: IUserDoc[] = await UserModel.find({});
      return ApiResponse.ok(request, response, { users });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async getOne(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const user: IUserDoc | null = await UserModel.findOne({ _id: request.params.id });
      if (user) {
        return ApiResponse.ok(request, response, { data: user });
      } else {
        return ApiResponse.notFound(request, response);
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async delete(request: Request, response: Response): Promise<any> {
    try {
      const { acknowledged, deletedCount } = await UserModel.deleteMany({});
      if (deletedCount > 0) {
        return ApiResponse.noContent(request, response);
      } else {
        return ApiResponse.notFound(request, response);
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deleteOne(request: Request, response: Response): Promise<any> {
    try {
      const user: IUserDoc | null = await UserModel.findOne({ _id: request.params.id });
      if (user) {
        const { acknowledged, deletedCount } = await UserModel.deleteOne({ _id: request.params.id });
        return ApiResponse.noContent(request, response);
      } else {
        return ApiResponse.notFound(request, response);
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async updateClientDownloadAccess(request: Request, response: Response): Promise<any> {
    try {
      let user: IUserDoc | null = await UserModel.findById(request.body.id);
      if (!user) {
        return ApiResponse.notFound(request, response, 'User not found.');
      } else {
        const set: any = {};
        const updateAbles: string[] = ['canDownloadGlobal', 'canDownloadPpts', 'canDownloadDocs', 'canDownloadPdfs', 'canDownloadVimeosVideos', 'canWatchVimeosVideos'];
        Object.keys(request.body).map((key: string) => {
          if (updateAbles.includes(key)) {
            set[key] = request.body[key];
          }
        });
        if (Object.keys(set).length) {
          set.updatedAt = new Date();
          user = await UserModel.findOneAndUpdate({ _id: request.body.id }, { $set: set }, { new: true, upsert: false });
          return ApiResponse.ok(request, response, { user, message: 'Client updated successfully.' });
        } else {
          return ApiResponse.badRequest(request, response, 'User found but nothing to update.');
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async getClientContent(request: Request, response: Response): Promise<any> {
    try {
      if (!request.query.id) {
        return ApiResponse.badRequest(request, response, `User id is required.`);
      } else {
        const user: IUserDoc | null = await UserModel.findOne({ _id: request.query.id, role: 'user' });
        if (!user) {
          return ApiResponse.badRequest(request, response, `User not found.`);
        } else {
          const cohortsWithFilteredModules = await CohortModel.find({})
            .populate({
              path: 'modules.users',
              match: { _id: user._id },
              select: 'name phone email',
            })
            .exec();

          const userCohorts = cohortsWithFilteredModules.reduce((result, cohort) => {
            const filteredModules = cohort.modules.filter((module) => module.users.some((user) => user._id.toString() === request.query.id));
            if (filteredModules.length > 0) {
              const filteredModulesWithoutUsers = filteredModules.map((module) => {
                const { users, ...moduleWithoutUsers } = module.toObject();
                return moduleWithoutUsers;
              });
              // @ts-ignore
              result.push({ ...cohort.toObject(), modules: filteredModulesWithoutUsers });
            }
            return result;
          }, []);
          const moduleAssignments = await ModuleAssignmentModel.find({ user: user._id })
            .populate({
              path: 'assignedBy',
              select: 'name phone email',
            })
            .populate({
              path: 'user',
              select: 'name phone email',
            })
            .exec();
          // return ApiResponse.ok(request, response, { cohorts: userCohorts, moduleAssignments });
          return ApiResponse.ok(request, response, { cohorts: userCohorts, moduleAssignments });
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default adminClientController;
