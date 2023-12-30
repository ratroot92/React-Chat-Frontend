/* eslint-disable @typescript-eslint/ban-ts-comment */
import { boolean } from 'joi';
import mongoose, { Document, Schema } from 'mongoose';

export enum ENUM_WHO_CAN_VIEW_POST {
  OWNER = 'OWNER',
  GROUP_MEMEBERS = 'GROUP_MEMEBERS',
}

export enum ENUM_WHO_CAN_POST {
  OWNER = 'OWNER',
  GROUP_MEMEBERS = 'GROUP_MEMEBERS',
}

export enum ENUM_GROUP_TYPES {
  OPEN = 'OPEN',
  CLOSED = 'CLOSED',
  MODERATED = 'MODERATED',
}

export interface IModeratorSettings {
  moderator: mongoose.Types.ObjectId;
  canDeletePost: boolean;
  canCreatePost: boolean;
  canModifyClients: boolean;
}

export interface IGroupMember {
  canPost: boolean;
  canComment: boolean;
  member: mongoose.Types.ObjectId;
}

export interface IGroup {
  name: string;
  avatar: string;
  description: string;
  // whoCanViewPost: ENUM_WHO_CAN_VIEW_POST;
  // whoCanPost: ENUM_WHO_CAN_POST;
  isModerated: boolean;
  groupOwners: mongoose.Types.ObjectId;
  moderatorSettings: IModeratorSettings;
  groupMembers: IGroupMember[];
  groupPosts: mongoose.Types.ObjectId[];
}

export interface IGroupDoc extends IGroup, Document {
  _id: string;
}

const schema: Schema<IGroup> = new Schema<IGroup>(
  {
    name: { type: String, required: true, trim: true, unique: true, minlength: 3, maxlength: 50 },
    avatar: { type: String, trim: true, default: null },
    description: { type: String, required: true, trim: true, minlength: 3, maxlength: 200 },
    // whoCanViewPost: {
    //   type: String,
    //   enum: Object.values(ENUM_WHO_CAN_VIEW_POST),
    //   required: true,
    // },
    // whoCanPost: { type: String, enum: Object.values(ENUM_WHO_CAN_POST), required: true },
    isModerated: { type: Boolean, required: true },
    moderatorSettings: {
      moderator: { type: Schema.Types.ObjectId, ref: 'User' },
      canDeletePost: { type: Boolean },
      canCreatePost: { type: Boolean },
      canModifyClients: { type: Boolean },
    },
    groupOwners: { type: Schema.Types.ObjectId, ref: 'User', required: true },
    groupMembers: [
      {
        member: { type: Schema.Types.ObjectId, ref: 'User', required: true },
        canPost: { type: Boolean, required: true },
        canComment: { type: Boolean, required: true },
        createdAt: { type: Date, default: Date.now() },
      },
    ],
    groupPosts: [{ type: Schema.Types.ObjectId, ref: 'Post' }],
  },
  {
    timestamps: true,
  }
);

export const GroupModel = mongoose.model<IGroupDoc>('Group', schema);
