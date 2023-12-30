/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Router, Request, Response } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import passport from '../../passport';
import { ISessionDoc, SessionModel } from '../../models/Session';
const AdminProtect = passport.authenticate('admin-jwt', { session: false });

const adminSessionController = {
  async createSession(request: Request, response: Response): Promise<any> {
    try {
      let session: ISessionDoc | null = await SessionModel.findOne({ name: request.body.name });
      if (session) {
        return ApiResponse.badRequest(request, response, `Session with name '${session.name}' already exists.`);
      }
      session = await SessionModel.create({
        name: request.body.name,
      });
      return ApiResponse.created(request, response, { session, message: 'Session created successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async getAllSessions(request: Request, response: Response): Promise<any> {
    try {
      // @ts-ignore
      const sessionId: string = request.query.id;
      if (sessionId) {
        const session: ISessionDoc | null = await SessionModel.findById(sessionId);
        if (!session) {
          return ApiResponse.notFound(request, response, 'Session not found.');
        }
        return ApiResponse.ok(request, response, { session });
      } else {
        const sessions: ISessionDoc[] = await SessionModel.find({});
        return ApiResponse.ok(request, response, { sessions });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async deleteSessionById(request: Request, response: Response): Promise<any> {
    try {
      const sessionId: string = request.body.id;

      let session: ISessionDoc | null = await SessionModel.findById(sessionId);
      if (!session) {
        return ApiResponse.notFound(request, response, 'Session not found.');
      } else {
        session = await SessionModel.findByIdAndDelete(session._id);
        return ApiResponse.ok(request, response, { session: session, message: 'Session deleted successfuly' });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async updateSessionById(request: Request, response: Response): Promise<any> {
    try {
      const sessionId: string = request.body.id;
      const name: string = request.body.name;
      let session: ISessionDoc | null = await SessionModel.findById(sessionId);
      if (!session) {
        return ApiResponse.notFound(request, response, 'Session not found.');
      } else {
        session = await SessionModel.findByIdAndUpdate({ _id: sessionId }, { name: name, updatedAt: new Date() }, { new: true, upsert: false });
        return ApiResponse.ok(request, response, { session: session, message: 'Session updated successfuly' });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default adminSessionController;
