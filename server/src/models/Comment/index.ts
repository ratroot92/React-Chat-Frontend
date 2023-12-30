import mongoose, { Document, Schema } from 'mongoose';

export interface IComment {
  content: string;
  post: mongoose.Types.ObjectId;
  user: mongoose.Types.ObjectId;
  likes: mongoose.Types.ObjectId[];
}

export interface ICommentDoc extends IComment, Document {
  _id: string;
}

const schema: Schema<IComment> = new Schema<IComment>(
  {
    content: { type: String, required: true, trim: true, minlength: 3, maxlength: 500 },
    post: { type: Schema.Types.ObjectId, ref: 'Post', required: true },
    user: { type: Schema.Types.ObjectId, ref: 'User', required: true },
    likes: [{ type: Schema.Types.ObjectId, ref: 'User', default: [] }],
  },
  {
    timestamps: true,
  }
);

// Define the virtual field 'likesCount'
// schema.virtual('likesCount').get(function () {
//   return this.likes.length;
// });
export const CommentModel = mongoose.model<ICommentDoc>('Comment', schema);
