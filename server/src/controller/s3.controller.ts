/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Request, Response } from 'express';
import multer from 'multer';
import path from 'path';
import { ApiResponse, fileUtils } from '../helpers';
import { FileModel, IS3FileDoc } from '../models/File';
import S3Util from '../utils/s3';

const s3Controller = {
  post: async (request: Request, response: Response) => {
    const fileFilter = (request: Request, file: any, cb: any) => (['.png', '.jpg'].includes(path.extname(file.originalname).toLowerCase()) ? cb(null, true) : cb(new Error('Only .png or .jpg files are allowed')));
    const storage = multer.diskStorage({
      destination: (request: Request, file: any, cb: any) => cb(null, `${process.cwd()}/public/temp`),
      filename: (request: Request, file: any, cb: any) => cb(null, Date.now() + path.extname(file.originalname)),
    });
    const upload = multer({ storage, limits: { fileSize: 10 * 1024 * 1024 } }).single('file');
    upload(request, response, async (err: any) => {
      try {
        if (err) {
          return ApiResponse.badRequest(request, response, err);
        }
        if (!request.file) {
          return ApiResponse.badRequest(request, response, 'Failed to upload file to S3.');
        }
        if (!request.body.fileType) {
          if (request.file) {
            if (request.file.path) {
              await fileUtils.autoUnlink(request.file.path);
            }
          }
          return ApiResponse.badRequest(request, response, 'fileType is required.');
        }
        const s3Util = new S3Util();
        const uploadedFile: any = await s3Util.uploadInBucket(request.file, request.body.fileType);
        const file: IS3FileDoc = await FileModel.create(uploadedFile);
        return ApiResponse.ok(request, response, { file, message: `File uploaded successfully.` });
      } catch (err) {
        console.log(err);
        if (request.file) {
          if (request.file.path) {
            await fileUtils.autoUnlink(request.file.path);
          }
        }
        return ApiResponse.internalServerError(request, response, err);
      }
    });
  },
  put: async (request: Request, response: Response) => {
    const fileFilter = (request: Request, file: any, cb: any) => (['.png', '.jpg'].includes(path.extname(file.originalname).toLowerCase()) ? cb(null, true) : cb(new Error('Only .png or .jpg files are allowed')));
    const storage = multer.diskStorage({
      destination: (request: Request, file: any, cb: any) => cb(null, `${process.cwd()}/public/temp`),
      filename: (request: Request, file: any, cb: any) => cb(null, Date.now() + path.extname(file.originalname)),
    });
    const upload = multer({ storage, limits: { fileSize: 5 * 1024 * 1024 } }).single('file');
    upload(request, response, async (err: any) => {
      try {
        if (err) {
          return ApiResponse.badRequest(request, response, err);
        }
        if (!request.file) {
          return ApiResponse.badRequest(request, response, 'Failed to upload file to S3.');
        }
        if (!request.body.fileId) {
          if (request.file) {
            if (request.file.path) {
              await fileUtils.autoUnlink(request.file.path);
            }
          }
          return ApiResponse.badRequest(request, response, 'fileId is required.');
        }
        let file: IS3FileDoc | null = await FileModel.findById(request.body.fileId);
        if (!file) {
          if (request.file) {
            if (request.file.path) {
              await fileUtils.autoUnlink(request.file.path);
            }
          }
          return ApiResponse.badRequest(request, response, 'file not found.');
        }

        const s3Util = new S3Util();
        const uploadedFile: any = await s3Util.updateInBucket(file, request.file);
        uploadedFile.updatedAt = Date.now();
        file = await FileModel.findByIdAndUpdate({ _id: file._id }, { $set: uploadedFile }, { new: true, upsert: false });
        return ApiResponse.ok(request, response, { file, message: `File updated successfully.` });
      } catch (err) {
        console.log(err);
        if (request.file) {
          if (request.file.path) {
            await fileUtils.autoUnlink(request.file.path);
          }
        }
        return ApiResponse.internalServerError(request, response, err);
      }
    });
  },

  delete: async (request: Request, response: Response) => {
    try {
      const fileId = request.body.fileId;
      if (!fileId) {
        return ApiResponse.badRequest(request, response, `'fileId' is required.`);
      }
      const file: IS3FileDoc | null = await FileModel.findById(fileId);
      if (!file) {
        return ApiResponse.badRequest(request, response, `File not found.`);
      }
      const s3Util = new S3Util();
      await s3Util.deleteFromS3Bucket(file.fileKey);
      await FileModel.findOneAndDelete({ _id: fileId });
      return ApiResponse.ok(request, response, { file, message: `File deleted successfully.` });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default s3Controller;
