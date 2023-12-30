/* eslint-disable @typescript-eslint/ban-ts-comment */
/* eslint-disable @typescript-eslint/no-this-alias */
import mongoose, { Schema, Document } from 'mongoose';

export interface IFile {
  title: string;
  url: string;
  isDownloadable: boolean;
  createdAt: Date;
  updatedAt: Date;
}
export interface IKeyFile {
  title: string;
  viewUrl: string;
  downloadUrl: string;
  isDownloadable: boolean;
  createdAt: Date;
  updatedAt: Date;
}

export interface IDocFile {
  title: string;
  viewUrl: string;
  downloadUrl: string;
  isDownloadable: boolean;
  createdAt: Date;
  updatedAt: Date;
}

export interface IPptFile {
  title: string;
  viewUrl: string;
  downloadUrl: string;
  isDownloadable: boolean;
  createdAt: Date;
  updatedAt: Date;
}

export interface ICohortFile extends IFile {
  _id: string;
}
/**
 * DAY
 */

export interface IDay extends Document {
  title: string;
  pdfs: ICohortFile[];
  videos: ICohortFile[];
  docs: IDocFile[];
  ppts: IPptFile[];
  keys: IKeyFile[];
  createdAt: Date;
  updatedAt: Date;
}

export interface IDayDoc extends IDay {
  _id: string;
}

const daySchema: Schema<IDay> = new Schema<IDay>({
  title: { type: String, required: true },
  videos: {
    type: [
      {
        url: String,
        title: String,
        isDownloadable: { type: Boolean, default: true },
        createdAt: { type: Date, default: Date.now() },
        updatedAt: { type: Date, default: Date.now() },
      },
    ],
    default: [],
  },
  pdfs: {
    type: [
      {
        url: String,
        title: String,
        isDownloadable: { type: Boolean, default: true },
        createdAt: { type: Date, default: Date.now() },
        updatedAt: { type: Date, default: Date.now() },
      },
    ],
    default: [],
  },
  docs: {
    type: [
      {
        viewUrl: String,
        downloadUrl: String,
        isDownloadable: { type: Boolean, default: true },
        createdAt: { type: Date, default: Date.now() },
        updatedAt: { type: Date, default: Date.now() },
      },
    ],
    default: [],
  },
  ppts: {
    type: [
      {
        viewUrl: String,
        downloadUrl: String,
        title: String,
        isDownloadable: { type: Boolean, default: true },
        createdAt: { type: Date, default: Date.now() },
        updatedAt: { type: Date, default: Date.now() },
      },
    ],
    default: [],
  },

  keys: {
    type: [
      {
        viewUrl: String,
        downloadUrl: String,
        title: String,
        isDownloadable: { type: Boolean, default: true },
        createdAt: { type: Date, default: Date.now() },
        updatedAt: { type: Date, default: Date.now() },
      },
    ],
    default: [],
  },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});
/**
 * MODULE
 */

export interface IModule extends Document {
  name: string;
  users: any[];
  days: IDayDoc[];
  displayOrder: number;
  createdAt: Date;
  updatedAt: Date;
}
export interface IModuleDoc extends IModule {
  _id: string;
}

const moduleSchema: Schema<IModule> = new Schema<IModule>({
  name: { type: String, required: true },
  days: { type: [daySchema] },
  users: [{ type: mongoose.Types.ObjectId, ref: 'User', default: [] }],
  displayOrder: { type: Number, required: true },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

/**
 * COHORT
 */

export interface ICohort extends Document {
  name: string;
  year: string;
  session: any;
  modules: IModuleDoc[];
  createdAt: Date;
  updatedAt: Date;
}
export interface ICohortDoc extends ICohort {
  _id: string;
}
const cohortSchema: Schema<ICohort> = new Schema<ICohort>({
  name: { type: String, required: true, unique: true, trim: true, maxlength: 25 },
  year: { type: String, required: true },
  // session: { type: String, required: true },
  session: { type: mongoose.Types.ObjectId, ref: 'Session', required: true },
  modules: { type: [moduleSchema], default: [] },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

export const CohortModel = mongoose.model<ICohortDoc>(`Cohort`, cohortSchema);

/**
 *
 *




const cohortSchema: Schema<ICohortDoc> = new Schema<ICohortDoc>({
  name: { required: true,trim:true, unique: true , trim:true},
  year: { required: true,trim:true },
  session: { required: true,trim:true },
  modules: { type: [moduleSchema], default: [] },
  // validate: {
  //     validator: function (modules:any) {
  //           //       // Check for duplicate module names within the cohort
  //       const modulesNames = modules.map((module:any) => module.name);
  //       return new Set(modulesNames).size === modulesNames.length;
  //     },
  //     message: 'Duplicate module names are not allowed within a cohort.',
  //   },
  // },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

 */
