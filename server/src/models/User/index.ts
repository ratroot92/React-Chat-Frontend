/* eslint-disable @typescript-eslint/ban-ts-comment */
/* eslint-disable @typescript-eslint/no-this-alias */
import mongoose, { Schema } from 'mongoose';
import bcryptJs from 'bcryptjs';

export interface IUser {
  name: string;
  email: any;
  password: string;
  phoneNo: string;
  termsAccepted: boolean;
  isLicensed: boolean;
  notes: string;
  isAdminApproved: boolean;
  role: string;
  isEmailInviteSent: boolean;
  avatar: string;
  passwordResetOtp: string;
  canDownloadGlobal: boolean;
  canDownloadPpts: boolean;
  canDownloadDocs: boolean;
  canDownloadPdfs: boolean;
  canDownloadVimeosVideos: boolean;
  canWatchVimeosVideos: boolean;
  createdAt: Date;
  updatedAt: Date;
  country: string;
  state: string;

  matchPassword: (password: string) => Promise<boolean>;
  maskPassword: () => IUserDoc;
}

export type OptionalProperties<T> = {
  [K in keyof T]?: T[K];
};

export type OptionalIUser = OptionalProperties<IUser>;

export interface IUserDoc extends IUser {
  _id: string;
  createdAt: Date;
  updatedAt: Date;
}

const userSchema: Schema<IUser> = new Schema<IUser>({
  name: { type: String, required: true },
  email: { type: String, required: true, unique: true, lowercase: true },
  phoneNo: { type: String, required: true },
  password: { type: String, required: true, select: false },
  termsAccepted: { type: Boolean, required: true },
  isLicensed: { type: Boolean, required: true, default: false },
  notes: { type: String, default: ' ' },
  isAdminApproved: { type: Boolean, default: false },
  role: { type: String, default: 'user' },
  isEmailInviteSent: { type: Boolean, default: false },
  avatar: { type: String, required: false },
  passwordResetOtp: { type: String, required: false, default: '' },
  canDownloadGlobal: { type: Boolean, default: true },
  canDownloadPpts: { type: Boolean, default: true },
  canDownloadDocs: { type: Boolean, default: true },
  canDownloadPdfs: { type: Boolean, default: true },
  canDownloadVimeosVideos: { type: Boolean, default: true },
  canWatchVimeosVideos: { type: Boolean, default: true },
  country: { type: String, required: false },
  state: { type: String, required: false },

  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

userSchema.pre('save', async function (next) {
  try {
    const user: any = this;
    if (!user.isModified('password')) next();
    const salt = await bcryptJs.genSalt(10);
    const hashedPassword = await bcryptJs.hash(this.password, salt);
    this.password = hashedPassword;
    return next();
  } catch (err) {
    // @ts-ignore
    return next(err);
  }
});

userSchema.methods.matchPassword = async function (password: string) {
  try {
    return await bcryptJs.compare(password, this.password);
  } catch (err) {
    // @ts-ignore
    throw new Error(err);
  }
};
userSchema.methods.maskPassword = function () {
  try {
    const user = JSON.parse(JSON.stringify(this));
    delete user.password;
    return user;
  } catch (err) {
    // @ts-ignore
    throw new Error(err);
  }
};

export const UserModel = mongoose.model<IUserDoc>(`User`, userSchema);
