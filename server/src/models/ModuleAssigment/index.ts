import mongoose, { Document, Schema } from 'mongoose';

export interface IModuleAssignment extends Document {
  assignedBy: mongoose.Types.ObjectId;
  module: mongoose.Types.ObjectId;
  user: mongoose.Types.ObjectId;
  comments: string;
  createdAt: Date;
  updatedAt: Date;
}

export interface IModuleAssignmentDoc extends IModuleAssignment {
  _id: string;
}

const schema: Schema<IModuleAssignment> = new Schema<IModuleAssignment>({
  assignedBy: { type: Schema.Types.ObjectId, ref: 'User', required: true },
  module: { type: Schema.Types.ObjectId, required: true },
  user: { type: Schema.Types.ObjectId, ref: 'User', required: true },
  comments: { type: String, default: '' },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

export const ModuleAssignmentModel = mongoose.model<IModuleAssignmentDoc>('ModuleAssignment', schema);
