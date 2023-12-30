/* eslint-disable @typescript-eslint/ban-ts-comment */
/* eslint-disable @typescript-eslint/no-this-alias */
import mongoose, { Schema } from 'mongoose';
import { Document } from 'mongoose';

export interface ISession extends Document {
  name: string;
}

export interface ISessionDoc extends ISession {
  _id: string;
  createdAt: Date;
  updatedAt: Date;
}

const schema: Schema<ISessionDoc> = new Schema<ISessionDoc>({
  name: { type: String, required: true, unique: true },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

export const SessionModel = mongoose.model<ISessionDoc>(`Session`, schema);
