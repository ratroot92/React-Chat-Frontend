import { Router, Request, Response, NextFunction } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import { IUserDoc, OptionalIUser, UserModel } from '../../models/User';
import passport from '../../passport';
import jwt from 'jsonwebtoken';
import { IVerifyOptions } from 'passport-local';

const clientAuthController = {
  async login(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      passport.authenticate('client-login', (err: any, user: IUserDoc, info: IVerifyOptions) => {
        if (err) {
          return next(err);
        }
        if (!user) {
          return ApiResponse.badRequest(request, response, info.message);
        }
        const accessToken = jwt.sign({ iss: process.env.JWT_ISSUER, sub: user }, process.env.JWT_CLIENT_SECRET, { expiresIn: '1d' });
        // response.cookie('access_token', accessToken, { httpOnly: true, sameSite: true });
        return ApiResponse.ok(request, response, { user, accessToken });
      })(request, request, next);
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async logout(request: Request, response: Response): Promise<any> {
    try {
      if (request.isAuthenticated()) {
        // response.clearCookie('access_token');
        return ApiResponse.ok(request, response, {});
      } else {
        return ApiResponse.unauthorized(request, response);
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async isAuthenticated(request: Request, response: Response): Promise<any> {
    try {
      if (request.isAuthenticated()) {
        return ApiResponse.ok(request, response, { user: request.user });
      } else {
        return ApiResponse.unauthorized(request, response);
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async register(request: Request, response: Response): Promise<any> {
    try {
      let user: IUserDoc | null = await UserModel.findOne({ email: request.body.email });

      if (user) {
        return ApiResponse.badRequest(request, response, 'User already exists.');
      }
      const { name, email, password, phoneNo, termsAccepted } = request.body;
      const newUser: OptionalIUser = {
        name: name,
        email: email,
        phoneNo: phoneNo,
        password: password,
        termsAccepted: termsAccepted,
      };

      user = await UserModel.create(newUser);
      if (user) {
        user = await UserModel.findById(user._id);
        return ApiResponse.created(request, response, { user: user, message: 'success' });
      } else {
        return ApiResponse.badRequest(request, response, 'Failed to register user.');
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default clientAuthController;
