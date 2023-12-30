/* eslint-disable @typescript-eslint/ban-ts-comment */
/* eslint-disable @typescript-eslint/no-this-alias */
import AWS from 'aws-sdk';
import mongoose, { Document, Schema } from 'mongoose';

export interface IS3File extends Document {
  fileKey: string;
  encoding: string;
  mimetype: string;
  s3PrivateUrl: string;
  s3PublicUrl: string;
  bucketName: string;
  size: number;
  originalName: string;
}

export interface IS3FileDoc extends IS3File {
  _id: string;
  createdAt: Date;
  updatedAt: Date;
}
// function setS3PublicUrl(s3PublicUrl: string, value2: string, schemaString: any) {
//   // incase of save called // this will be called before save so you can modify value
//   return s3PublicUrl;
// }

// function getS3PublicUrl(s3PublicUrl: string, value2: string, schemaString: any) {
//   return s3PublicUrl;
// }

//@ts-ignore
const schema: Schema<IS3FileDoc> = new Schema<IS3FileDoc>({
  //@ts-ignore
  fileKey: { type: String, required: true },
  originalName: { type: String, required: true },
  encoding: { type: String, required: true },
  mimetype: { type: String, required: true },
  s3PrivateUrl: { type: String, required: true },
  //@ts-ignore
  // s3PublicUrl: { type: String, required: false, set: setS3PublicUrl, get: getS3PublicUrl },
  s3PublicUrl: { type: String, required: false },

  size: { type: Number, required: true },
  bucketName: { type: String, required: true },
  createdAt: { type: Date, default: Date.now() },
  updatedAt: { type: Date, default: Date.now() },
});

schema.statics.deleteManyAndHandleS3 = async function (filter) {
  const documentsToRemove = await this.find(filter);
  try {
    const s3 = new AWS.S3();
    const bucketName = 'ish-dev-s3-bucket';
    await Promise.all(
      documentsToRemove.map(async (document: any) => {
        const objectKey = document.fileKey;
        await s3.deleteObject({ Bucket: bucketName, Key: objectKey }).promise();
        console.log('File deleted successfully from S3:', objectKey);
      })
    );

    return this.deleteMany(filter);
  } catch (err) {
    console.error('Error deleting file from S3:', err);
    throw err; // Throw the error to handle it at the caller level
  }
};

// schema.set('toObject', { getters: true });
// schema.set('toJSON', { getters: true });

export const FileModel = mongoose.model<IS3FileDoc>(`File`, schema);
