/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Router, Request, Response, NextFunction } from 'express';
import { ApiResponse } from '../../helpers/api';
import { AppRoute } from '../../app-route';
import { IUserDoc, OptionalIUser, UserModel } from '../../models/User';
import bcryptJs from 'bcryptjs';
import { ProtectClient } from '../../passport';
import { fileUtils } from '../../helpers/file-utils';
import multer from 'multer';
import path from 'path';
import { ENUM_EMAIL_TEMPLATES, IEmailTemplateData, IMailOptions, emailManager, ensureDir } from '../../helpers';
import { CohortModel, ICohortDoc, IModuleDoc } from '../../models/Cohort';
import CustomErrorHandler from '../../helpers/error-handler';
import { GroupModel, IGroupDoc } from '../../models/Group';

const clientUserController = {
  avatarStorage: multer.diskStorage({
    // @ts-ignore
    destination(request: Request, file: any, cb: any) {
      return cb(null, `${process.cwd()}/public/user/avatar`);
    },
    filename(request: Request, file: any, cb: any) {
      return cb(null, Date.now() + path.extname(file.originalname)); // Appending extension
    },
  }),
  // @ts-ignore
  upload = multer({ storage: this.avatarStorage }).single('avatar'),
  async create(request: Request, response: Response): Promise<any> {
    try {
      let user: IUserDoc | null = await UserModel.findOne({ email: request.body.email });
      if (user) {
        return ApiResponse.badRequest(request, response, 'User already exists.');
      }
      const { name, email, password, phoneNo, termsAccepted } = request.body;
      const salt = await bcryptJs.genSalt(10);
      const hash = await bcryptJs.hash(password, salt);
      const newUser: OptionalIUser = {
        name: name,
        email: email,
        phoneNo: phoneNo,
        password: hash,
        termsAccepted: termsAccepted,
      };
      user = await UserModel.create(newUser);
      if (user) {
        user = await UserModel.findById(user._id);
        return ApiResponse.created(request, response, { data: user, message: 'success' });
      } else {
        return ApiResponse.badRequest(request, response, 'Failed to create user.');
      }
    } catch (err) {
      return ApiResponse.badRequest(request, response, {});
    }
  },
  async getClients(request: Request, response: Response, net: NextFunction) {
    try {
      const users = await UserModel.find({ role: 'user', isAdminApproved: true }).populate('_id name email avatar phoneNo role country state');
      return ApiResponse.ok(request, response, users);
    } catch (err) {
      return ApiResponse.badRequest(request, response, {});
    }
  },
  /**
             * {
                fieldname: 'avatar',
                originalname: 'Screenshot from 2023-06-27 07-25-33.png',
                encoding: '7bit',
                mimetype: 'image/png',
                buffer: <Buffer 89 50 4e 47 0d 0a 1a 0a 00 00 00 0d 49 48 44 52 00 00 02 f4 00 00 01 94 08 06 00 00 00 5a c0 b6 d1 00 00 00 04 73 42 49 54 08 08 08 08 7c 08 64 88 00 ... 46435 more bytes>,
                size: 46485
               }
             */
  async forgotPassword(request: Request, response: Response): Promise<any> {
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

        return ApiResponse.ok(request, response, {});
      }
    } catch (err) {
      return ApiResponse.badRequest(request, response, {});
    }
  },
  async verifyOtp(request: Request, response: Response): Promise<any> {
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
  async resetPassword(request: Request, response: Response): Promise<any> {
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
          // @ts-ignore
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
          return ApiResponse.ok(request, response, { message: 'Password updated successfully.' });

          // response.clearCookie('access_token');
        }
      } else {
        return ApiResponse.badRequest(request, response, '');
      }
    } catch (err) {
      return ApiResponse.badRequest(request, response, {});
    }
  },
  async updateUserAvatar(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      //@ts-ignore
      let user: IUserDoc | null = await UserModel.findById(request.user._id);
      if (!user) return next(new CustomErrorHandler('User not found', 400));
      if (!request.file) return next(new CustomErrorHandler('Avatar not found', 400));
      if (user.avatar) {
        //@ts-ignore
        await fileUtils.unlinkAsync(`${process.cwd()}${user.avatar.replace(process.env.APP_DOMAIN, '')}`);
      }
      //@ts-ignore
      user = await UserModel.findByIdAndUpdate({ _id: request.user._id }, { avatar: request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN) }, { new: true, upsert: false });
      return ApiResponse.ok(request, response, user);
    } catch (err) {
      return ApiResponse.badRequest(request, response, {});
    }
  },
  async seed(request: Request, response: Response): Promise<any> {
    try {
      await UserModel.deleteMany({});
      // const users: IUserDoc[] = [
      const users: any[] = [
        {
          name: 'ahmed kabeer shaukat',
          email: 'tech.minwallamodel@gmail.com',
          phoneNo: '03441500542',
          password: 'pakistan123>',
          termsAccepted: true,
        },
        {
          name: 'alan',
          email: 'alan@evergreen.com',
          password: 'pakistan123>',
          role: 'user',
          phoneNo: '03441500542',
          termsAccepted: true,
        },
        {
          name: 'bob',
          email: 'bob@evergreen.com',
          password: 'pakistan123>',
          role: 'user',
          phoneNo: '03441500542',
          termsAccepted: true,
        },
        {
          name: 'calum',
          email: 'calum@evergreen.com',
          password: 'pakistan123>',
          role: 'user',
          phoneNo: '03441500542',
          termsAccepted: true,
        },
      ];
      let seeds: IUserDoc[] = await Promise.all(users.map(async (user: IUserDoc) => UserModel.create(user)));
      seeds = await UserModel.find({}).populate('role');
      return ApiResponse.ok(request, response, seeds);
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async updateUserPartial(request: Request, response: Response): Promise<any> {
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
  async getClientContent(request: Request, response: Response): Promise<any> {
    try {
      const cohortsWithFilteredModules: ICohortDoc[] = await CohortModel.find({})
        .populate([
          {
            path: 'modules.users',
            //@ts-ignore
            match: { _id: request.user._id },
            select: 'name phone email',
          },
          { path: 'session', model: 'Session' },
        ])
        .exec();

      const userCohorts = cohortsWithFilteredModules.reduce((result: IModuleDoc[], cohort: ICohortDoc) => {
        //@ts-ignore
        const filteredModules = cohort.modules.filter((module) => module.users.some((user) => user._id.toString() === request.user._id));
        if (filteredModules.length > 0) {
          //@ts-ignore
          const filteredModulesWithoutUsers = filteredModules.map((module) => {
            //@ts-ignore
            const { users, ...moduleWithoutUsers } = module.toObject();
            return moduleWithoutUsers;
          });
          //@ts-ignore
          result.push({ ...cohort.toObject(), modules: filteredModulesWithoutUsers });
        }
        return result;
      }, []);
      return ApiResponse.ok(request, response, { cohorts: userCohorts });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async getClientGroups(request: Request, response: Response): Promise<any> {
    try {
      if (request.query.id) {
        //@ts-ignore
        // const group: IGroupDoc = await GroupModel.find({ 'groupMembers.member': request.user._id })
        const group: IGroupDoc = await GroupModel.findOne({ _id: request.query.id })

          .populate({
            path: 'moderatorSettings.moderator',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'groupOwners',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'groupMembers.member',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'groupPosts',
            populate: [
              {
                path: 'media',
              },
            ],
          });
        if (!group) {
          return ApiResponse.badRequest(request, response, `Group not found.`);
        }
        return ApiResponse.ok(request, response, { group, message: 'Success' });
      } else {
        //@ts-ignore

        //@ts-ignore
        const groups: IGroupDoc[] = await GroupModel.find({ 'groupMembers.member': request.user._id })
          .populate({
            path: 'moderatorSettings.moderator',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'groupOwners',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'groupMembers.member',
            select: '_id name email avatar phoneNo role country state',
          })
          .populate({
            path: 'groupPosts',
            populate: [
              {
                path: 'media',
              },
            ],
          });
        return ApiResponse.ok(request, response, { groups, message: 'Success' });
      }
    } catch (err) {
      //@ts-ignore
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default clientUserController;
