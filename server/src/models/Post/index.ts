/* eslint-disable @typescript-eslint/ban-ts-comment */
import mongoose, { Document, Schema } from 'mongoose';

export interface IPost {
  group: mongoose.Types.ObjectId;
  content: string;
  title: string;
  postCreator: mongoose.Types.ObjectId;
  isGroupAdminApproved: boolean;
  comments: mongoose.Types.ObjectId[];
  likes: mongoose.Types.ObjectId[];
  media: mongoose.Types.ObjectId;
}

export interface IPostDoc extends IPost, Document {
  _id: string;
}

const schema: Schema<IPost> = new Schema<IPost>(
  {
    title: { type: String, required: true, trim: true, minlength: 1, maxlength: 100 },
    content: { type: String, trim: true, maxlength: 500, default: '' },
    group: { type: Schema.Types.ObjectId, ref: 'Group', required: true },
    postCreator: { type: Schema.Types.ObjectId, ref: 'User', required: true },
    isGroupAdminApproved: { type: Boolean, default: false },
    comments: [{ type: Schema.Types.ObjectId, ref: 'Comment', default: [] }],
    likes: [{ type: Schema.Types.ObjectId, ref: 'User', default: [] }],
    media: { type: Schema.Types.ObjectId, ref: 'File', default: null },
  },
  {
    timestamps: true,
  }
);

export const PostModel = mongoose.model<IPostDoc>('Post', schema);
