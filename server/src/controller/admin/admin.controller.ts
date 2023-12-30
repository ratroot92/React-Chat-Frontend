/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Request, Response, NextFunction } from 'express';
import { ApiResponse } from '../../helpers/api';
import { IUserDoc, UserModel } from '../../models/User';
import { ENUM_EMAIL_TEMPLATES, IEmailTemplateData, IMailOptions, emailManager, ensureDir, fileUtils } from '../../helpers';
import CustomErrorHandler from '../../helpers/error-handler';

const adminUserController = {
  updateUserPartial: async function (request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      let user: IUserDoc | null = await UserModel.findById(request.body.id);
      if (!user) {
        return ApiResponse.notFound(request, response, 'User not found.');
      } else {
        const set: any = {};
        const updateAbles: string[] = ['name', 'phoneNo', 'notes', 'country', 'state'];
        Object.keys(request.body).map((key: string) => {
          if (updateAbles.includes(key)) {
            set[key] = request.body[key];
          }
        });
        if (Object.keys(set).length) {
          set.updatedAt = new Date();
          user = await UserModel.findOneAndUpdate({ _id: request.body.id }, { $set: set }, { new: true, upsert: false });
          return ApiResponse.ok(request, response, { user });
        } else {
          return ApiResponse.badRequest(request, response, 'User found but nothing to update.');
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  getAllAdmins: async function (request: Request, response: Response): Promise<any> {
    try {
      if (request.query.id) {
        const user: IUserDoc | null = await UserModel.findOne({ _id: request.query.id, role: 'admin' });
        if (!user) {
          return ApiResponse.notFound(request, response, `Admin not found.`);
        } else {
          return ApiResponse.ok(request, response, { user });
        }
      } else {
        const users: IUserDoc[] = await UserModel.find({ role: 'admin' });
        return ApiResponse.ok(request, response, { users });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  forgotPassword: async function (request: Request, response: Response): Promise<any> {
    try {
      const email: string = request.body.email;
      const user: IUserDoc | null = await UserModel.findOne({ email });
      if (!user) {
        return ApiResponse.badRequest(request, response, 'User not found.');
      } else {
        user.passwordResetOtp = Math.floor(Math.random() * 10000)
          .toString()
          .padStart(4, '0');
        // @ts-ignore
        await user.save();
        const templateOptons: IEmailTemplateData = {
          recipient: user.email,
          template: ENUM_EMAIL_TEMPLATES.RESET_CLIENT_PASSWORD,
          info: { user },
        };
        const mailOptions: IMailOptions = emailManager.getEmailTemplate(templateOptons);
        await emailManager.send(mailOptions);
        return ApiResponse.ok(request, response, { message: 'Otp sent successfully.' });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  verifyOtp: async function (request: Request, response: Response): Promise<any> {
    try {
      const otp: any = request.body.otp;
      const email: any = request.body.email;
      const user: IUserDoc | null = await UserModel.findOne({ email });
      if (!user) {
        return ApiResponse.badRequest(request, response, 'User not found.');
      } else {
        if (user.passwordResetOtp === otp) {
          return ApiResponse.ok(request, response, 'Otp verified successfully.');
        } else {
          return ApiResponse.badRequest(request, response, 'Invalid Otp.');
        }
      }
    } catch (err) {
      return ApiResponse.badRequest(request, response, {});
    }
  },

  resetPassword: async function (request: Request, response: Response): Promise<any> {
    try {
      const password: any = request.body.password;
      const confirmPassword: any = request.body.confirmPassword;
      const email: any = request.body.email;
      if (password && confirmPassword && email) {
        const user: IUserDoc | null = await UserModel.findOne({ email });
        if (!user) {
          return ApiResponse.badRequest(request, response, 'User not found.');
        } else {
          if (!user.passwordResetOtp) {
            return ApiResponse.badRequest(request, response, 'You have already changed your password.');
          }
          user.password = password;
          user.passwordResetOtp = '';
          // @ts-ignore
          await user.save();
          const templateOptons: IEmailTemplateData = {
            recipient: user.email,
            template: ENUM_EMAIL_TEMPLATES.CLIENT_PASSWORD_CHANGED,
            info: { user },
          };
          const mailOptions: IMailOptions = emailManager.getEmailTemplate(templateOptons);
          // await emailManager.send(mailOptions);
          return ApiResponse.ok(request, response, {});
        }
      } else {
        return ApiResponse.badRequest(request, response, '');
      }
    } catch (err) {
      return ApiResponse.badRequest(request, response, {});
    }
  },

  updateUserAvatar: async function (request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      //@ts-ignore
      let user: IUserDoc | null = await UserModel.findById(request.user._id);
      if (!user) return next(new CustomErrorHandler('User not found', 400));
      if (!request.file) return next(new CustomErrorHandler('Avatar not found', 400));
      if (user.avatar) {
        //@ts-ignore
        if (await fileUtils.existAsync(`${process.cwd()}${user.avatar.replace(process.env.APP_DOMAIN, '')}`)) {
          //@ts-ignore
          await fileUtils.unlinkAsync(`${process.cwd()}${user.avatar.replace(process.env.APP_DOMAIN, '')}`);
        }
      }

      //@ts-ignore
      //@ts-ignore
      user = await UserModel.findByIdAndUpdate({ _id: request.user._id }, { avatar: request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN) }, { new: true, upsert: false });
      return ApiResponse.ok(request, response, user);
    } catch (err) {
      return ApiResponse.badRequest(request, response, {});
    }
  },
};

export default adminUserController;
