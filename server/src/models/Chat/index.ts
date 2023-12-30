/* eslint-disable @typescript-eslint/ban-ts-comment */
/* eslint-disable @typescript-eslint/no-this-alias */
import mongoose, { Document, Schema } from 'mongoose';

export interface IChat extends Document {
  chatName: string;
  isGroupChat: boolean;
  users: mongoose.Schema.Types.ObjectId[];
  groupAdmin: mongoose.Schema.Types.ObjectId;
  lastMessage: mongoose.Schema.Types.ObjectId;
}

export interface IChatDoc extends IChat {
  _id: string;
  createdAt: Date;
  updatedAt: Date;
}

const schema: Schema<IChatDoc> = new Schema<IChatDoc>({
  chatName: { type: String, required: true },
  isGroupChat: { type: Boolean, required: true },
  users: [{ type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true }],
  groupAdmin: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  lastMessage: { type: mongoose.Schema.Types.ObjectId, ref: 'Message' },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

export const ChatModel = mongoose.model<IChatDoc>(`Chat`, schema);
