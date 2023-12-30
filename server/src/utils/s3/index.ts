/* eslint-disable no-async-promise-executor */
import AWS, { S3 } from 'aws-sdk';
import path from 'path';
import { fileUtils } from '../../helpers';

import { parse } from 'url';
import { FileModel } from '../../models/File';
interface IRequestFile {
  encoding: string;
  mimetype: string;
  size: number;
  path: string;
  originalname: string;
}

interface IS3PutObjectDetails {
  ETag: string;
  ServerSideEncryption: string;
  Location: string;
  key: string;
  Key: string;
  Bucket: string;
}

interface IReturnedS3File {
  fileKey: string;
  encoding: string;
  mimetype: string;
  s3PrivateUrl: string;
  s3PublicUrl: string;
  bucketName: string;
  size: number;
  originalName: string;
}

class S3Util {
  private bucketName: string;
  private signedUrlExpiry: number;
  private s3: S3;
  private fileNamePrefix = 'upload';
  private ALLOWED_VIDEO_EXTENSIONS = ['.mp4', '.webm', '.avi', '.mov', '.mkv', '.flv'];
  private ALLOWED_IMAGE_EXTENSIONS = ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.svg'];

  constructor() {
    this.bucketName = process.env.S3_BUCKET_NAME || 'ish-dev-s3-bucket';
    this.signedUrlExpiry = Number(process.env.S3_SIGNED_URL_EXPIRY || 500);
    this.s3 = new AWS.S3({
      accessKeyId: process.env.AWS_ACCESS_KEY_ID || 'AKIASJKZ637JDZSGPOFO',
      secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY || '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',
      region: 'us-east-1',
    });
  }

  public async upload(filePath: string, fileKey: string, contentType: string): Promise<IS3PutObjectDetails> {
    return new Promise(async (resolve, reject) => {
      try {
        const fileContent: any = await fileUtils.readFileAsync(filePath);
        await fileUtils.autoUnlink(filePath);
        this.s3.upload({ Bucket: this.bucketName, Body: fileContent, Key: fileKey, ContentType: contentType }, (err: any, data: any) => {
          if (err) {
            return reject(err);
          } else {
            return resolve(data);
          }
        });
      } catch (err) {
        return reject(err);
      }
    });
  }

  public async uploadInBucket(file: IRequestFile, s3Folder: string): Promise<IReturnedS3File> {
    // if (!filePath || filePath === '') throw new Error(`'filePath' is required.`);
    // if (!fileKey || fileKey === '') throw new Error(`'fileKey' is required.`);
    const encoding: string = file.encoding;
    const mimetype: string = file.mimetype;
    const size: number = file.size;
    const serverStoragePath: string = file.path;
    const extensionName = path.extname(file.originalname);
    let contentType = null;

    if (this.ALLOWED_VIDEO_EXTENSIONS.includes(extensionName)) {
      contentType = 'video/' + extensionName.split('.').join('');
    } else if (this.ALLOWED_IMAGE_EXTENSIONS.includes(extensionName)) {
      contentType = 'image/' + extensionName.split('.').join('');
    } else {
      // throw new Error(`Unkown / Unhandled file extension ${extensionName}.`);
      contentType = mimetype;
    }

    const uniqueFileName = this.generateUniqueFileName();
    const fileKey = s3Folder + '/' + uniqueFileName + '.' + mimetype.split('/')[1];
    const s3UploadDetails: IS3PutObjectDetails = await this.upload(serverStoragePath, fileKey, contentType);
    const s3PublicUrl: string = await this.getSignedUrl(fileKey, '');
    return {
      fileKey,
      originalName: file.originalname,
      encoding: encoding,
      mimetype: mimetype,
      s3PrivateUrl: s3UploadDetails.Location,
      s3PublicUrl: s3PublicUrl,
      bucketName: s3UploadDetails.Bucket,
      size: size,
    };
  }

  public async updateInBucket(existingFile: any, file: IRequestFile): Promise<IReturnedS3File> {
    // if (!filePath || filePath === '') throw new Error(`'filePath' is required.`);
    // if (!fileKey || fileKey === '') throw new Error(`'fileKey' is required.`);
    const encoding: string = file.encoding;
    const mimetype: string = file.mimetype;
    const size: number = file.size;
    const serverStoragePath: string = file.path;
    const extensionName = path.extname(file.originalname);
    let contentType = null;
    if (this.ALLOWED_VIDEO_EXTENSIONS.includes(extensionName)) {
      contentType = 'video/' + extensionName.split('.').join('');
    } else if (this.ALLOWED_IMAGE_EXTENSIONS.includes(extensionName)) {
      contentType = 'image/' + extensionName.split('.').join('');
    } else {
      // throw new Error(`Unkown / Unhandled file extension ${extensionName}.`);
      contentType = mimetype;
    }
    const s3UploadDetails: IS3PutObjectDetails = await this.upload(serverStoragePath, existingFile.fileKey, contentType);
    const s3PublicUrl: string = await this.getSignedUrl(existingFile.fileKey, '');
    return {
      fileKey: existingFile.fileKey,
      originalName: file.originalname,
      encoding: encoding,
      mimetype: mimetype,
      s3PrivateUrl: s3UploadDetails.Location,
      s3PublicUrl: s3PublicUrl,
      bucketName: s3UploadDetails.Bucket,
      size: size,
    };
  }

  public async deleteFromS3Bucket(fileKey: string): Promise<any> {
    const params = {
      Bucket: this.bucketName,
      Key: fileKey,
    };
    return new Promise((resolve, reject) => {
      this.s3.deleteObject(params, (err, data) => {
        if (err) {
          return reject(err);
        } else {
          return resolve(data);
        }
      });
    });
  }

  public isUrlExpired(preSignedUrl: string) {
    const parsedUrl = parse(preSignedUrl, true); // 'true' to parse query parameters
    const expirationTime = parsedUrl.query['Expires'];
    if (!expirationTime) {
      throw new Error('Expiration time not found in the URL.');
    }
    const currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
    //@ts-ignore
    return parseInt(expirationTime, 10) < currentTime;
  }

  public async getSignedUrl(fileKey: string, s3PublicUrl: string): Promise<string> {
    if (!fileKey || fileKey === '') throw new Error(`'fileKey' is required.`);
    const params = {
      Bucket: this.bucketName,
      Key: fileKey,
      Expires: this.signedUrlExpiry,
    };
    if (s3PublicUrl) {
      if (this.isUrlExpired(s3PublicUrl)) {
        const newS3PublicUrl = this.s3.getSignedUrl('getObject', params);
        await FileModel.findOneAndUpdate({ s3PublicUrl: s3PublicUrl }, { $set: { s3PublicUrl: newS3PublicUrl } });
        return newS3PublicUrl;
      } else {
        return s3PublicUrl;
      }
    } else {
      return this.s3.getSignedUrl('getObject', params);
    }
  }

  public generateUniqueFileName(): string {
    const timestamp = new Date().toISOString().replace(/[-T:Z.]/g, '');
    const randomString = Math.random().toString(36).substring(2, 8);
    const uniqueFileName = `${this.fileNamePrefix}_${timestamp}_${randomString}`;
    return uniqueFileName;
  }
}
export default S3Util;
