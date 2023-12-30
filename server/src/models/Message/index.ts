/* eslint-disable @typescript-eslint/ban-ts-comment */
/* eslint-disable @typescript-eslint/no-this-alias */
import mongoose, { Document, Schema } from 'mongoose';

export interface IMessgae extends Document {
  sender: mongoose.Schema.Types.ObjectId;
  reciever: mongoose.Schema.Types.ObjectId;
  chat: mongoose.Schema.Types.ObjectId;
  content: string;
  isDelivered: boolean;
  isSeen: boolean;
}

export interface IMessageDoc extends IMessgae {
  _id: string;
  createdAt: Date;
  updatedAt: Date;
}

const schema: Schema<IMessageDoc> = new Schema<IMessageDoc>({
  sender: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  reciever: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  chat: { type: mongoose.Schema.Types.ObjectId, ref: 'Chat' },
  content: { type: String, required: true },
  isDelivered: { type: Boolean, default: false },
  isSeen: { type: Boolean, default: false },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

export const MessageModel = mongoose.model<IMessageDoc>(`Message`, schema);
