/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Response, Request, NextFunction } from 'express';
import { ApiResponse } from './api';

export const isAdminGroupOwner = (request: Request, response: Response, next: NextFunction) => {
  //@ts-ignore
  const userId = request.user._id;
  //@ts-ignore
  const groupId = request.group._id.toString();
  if (userId !== groupId) {
    return ApiResponse.badRequest(request, response, `Unauthorized Access!.`);
  } else {
    return next();
  }
};
