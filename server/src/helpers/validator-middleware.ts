import { Response, Request, NextFunction } from 'express';
import { routesValidations } from '../validation';
import { ApiResponse } from './api';
import mongoose from 'mongoose';

function validateRequest() {
  return (request: Request, response: Response, next: NextFunction) => {
    let requestSlug = `${request.method.toLocaleUpperCase()}:${request.url}`;
    // const objectIdPattern = /[0-9a-fA-F]{24}/;
    // // @ts-ignore
    // if (mongoose.Types.ObjectId.isValid(requestSlug.match(objectIdPattern)[0])) {
    //     // @ts-ignore
    //     requestSlug = requestSlug.replace(requestSlug.match(objectIdPattern)[0], ':id');
    // }
    const schema = routesValidations[requestSlug];
    if (!schema) return next();
    else {
      const { error } = schema.validate(request.body);
      if (error) {
        const errorMessage = error.details.map((detail: any) => detail.message).join(', ');
        return ApiResponse.badRequest(request, response, errorMessage);
      } else {
        return next();
      }
    }
  };
}

export { validateRequest };
