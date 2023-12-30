// /* eslint-disable @typescript-eslint/ban-ts-comment */
// import mongoose, { Document, Schema } from 'mongoose';

// export interface IUserRequired {
//   name: string;
//   email: any;
//   password: string;
//   phoneNo: string;
//   termsAccepted: boolean;
//   role?: string;
//   isAdminApproved?: boolean;
// }
// export interface IUser {
//   name: string;
//   email: any;
//   password: string;
//   phoneNo: string;
//   termsAccepted: boolean;
//   isLicensed: boolean;
//   notes: string;
//   isAdminApproved: boolean;
//   role: string;
//   isEmailInviteSent: boolean;
//   avatar: string;
//   passwordResetOtp: string;
//   canDownloadGlobal: boolean;
//   canDownloadPpts: boolean;
//   canDownloadDocs: boolean;
//   canDownloadPdfs: boolean;
//   canDownloadVimeosVideos: boolean;
//   canWatchVimeosVideos: boolean;
//   country: string;
//   state: string;

//   matchPassword: (password: string) => Promise<boolean>;
//   maskPassword: () => IUserDoc;
// }

// export interface IUserDoc extends IUser, Document {
//   _id: string;
// }

// const schema: Schema<IUser> = new Schema<IUser>(
//   {
//     name: { type: String, required: true },
//     email: { type: String, required: true, unique: true },
//     phoneNo: { type: String, required: true },
//     password: { type: String, required: true, select: false },
//     termsAccepted: { type: Boolean, required: true },
//     isLicensed: { type: Boolean, required: true, default: false },
//     notes: { type: String, default: ' ' },
//     isAdminApproved: { type: Boolean, default: false },
//     role: { type: String, default: 'user' },
//     isEmailInviteSent: { type: Boolean, default: false },
//     avatar: { type: String, required: false },
//     passwordResetOtp: { type: String, required: false, default: '' },
//     canDownloadGlobal: { type: Boolean, default: true },
//     canDownloadPpts: { type: Boolean, default: true },
//     canDownloadDocs: { type: Boolean, default: true },
//     canDownloadPdfs: { type: Boolean, default: true },
//     canDownloadVimeosVideos: { type: Boolean, default: true },
//     canWatchVimeosVideos: { type: Boolean, default: true },
//     country: { type: String, required: false },
//     state: { type: String, required: false },
//   },
//   {
//     timestamps: true,
//   }
// );

// export const UserModel = mongoose.model<IUserDoc>('User', schema);
