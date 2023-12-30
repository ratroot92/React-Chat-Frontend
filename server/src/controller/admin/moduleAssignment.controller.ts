/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Router, Request, Response } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import passport from '../../passport';
import { IModuleAssignmentDoc, ModuleAssignmentModel } from '../../models/ModuleAssigment';
import { CohortModel, ICohortDoc, IModuleDoc } from '../../models/Cohort';
const AdminProtect = passport.authenticate('admin-jwt', { session: false });

const adminModuleAssigmentController = {
  async createModuleAssignment(request: Request, response: Response): Promise<any> {
    try {
      let assignment: IModuleAssignmentDoc | null = await ModuleAssignmentModel.findOne({ name: request.body.name });
      if (assignment) {
        // @ts-ignore
        return ApiResponse.badRequest(request, response, `ModuleAssignment with name '${assignment.name}' already exists.`);
      }
      assignment = await ModuleAssignmentModel.create({
        name: request.body.name,
      });
      return ApiResponse.created(request, response, { assignment, message: 'Assignment created successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async getAllModuleAssignments(request: Request, response: Response): Promise<any> {
    try {
      // @ts-ignore
      const assignmentId: string = request.query.id;
      if (assignmentId) {
        const assignment: IModuleAssignmentDoc | null = await ModuleAssignmentModel.findById(assignmentId).populate([
          { path: 'assignedBy', model: 'User', select: 'name email phoneNo' },
          { path: 'user', model: 'User', select: 'name email phoneNo' },
        ]);
        if (!assignment) {
          return ApiResponse.notFound(request, response, 'Assignment not found.');
        }
        return ApiResponse.ok(request, response, { assignment });
      } else {
        const assignments: IModuleAssignmentDoc[] = await ModuleAssignmentModel.find({});
        return ApiResponse.ok(request, response, { assignments });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async deleteModuleAssignmentById(request: Request, response: Response): Promise<any> {
    try {
      const sessionId: string = request.body.id;

      let assignment: IModuleAssignmentDoc | null = await ModuleAssignmentModel.findById(sessionId);
      if (!assignment) {
        return ApiResponse.notFound(request, response, 'Assignment not found.');
      } else {
        assignment = await ModuleAssignmentModel.findByIdAndDelete(assignment._id);
        return ApiResponse.ok(request, response, { assignment: assignment, message: 'Assignment deleted successfuly' });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async updateModuleAssignmentById(request: Request, response: Response): Promise<any> {
    try {
      const sessionId: string = request.body.id;
      const name: string = request.body.name;
      let assignment: IModuleAssignmentDoc | null = await ModuleAssignmentModel.findById(sessionId);
      if (!assignment) {
        return ApiResponse.notFound(request, response, 'Assignment not found.');
      } else {
        assignment = await ModuleAssignmentModel.findByIdAndUpdate({ _id: sessionId }, { name: name, updatedAt: new Date() }, { new: true, upsert: false });
        return ApiResponse.ok(request, response, { assignment: assignment, message: 'Assignment updated successfuly' });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deleteAllModuleAssignments(request: Request, response: Response): Promise<any> {
    try {
      await ModuleAssignmentModel.deleteMany({});
      const cohorts: ICohortDoc[] = await CohortModel.find({});
      await Promise.all(
        cohorts.map((cohort: ICohortDoc) => {
          if (cohort.modules.length) {
            cohort.modules.map((module: IModuleDoc) => {
              module.users = [];
            });
          }
          return cohort.save();
        })
      );
      return ApiResponse.ok(request, response, {}, `Deleted all module assignments successfully.`);
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default adminModuleAssigmentController;
