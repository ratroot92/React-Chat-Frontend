import { NextFunction, Request, Response } from 'express';
import { ICustomErrorHandler } from './error-handler';

function ErrorHandlerMiddleware(error: ICustomErrorHandler, request: Request, response: Response, next: NextFunction) {
  error.statusCode = error.statusCode || 500;
  error.message = error.message || 'Internal Server Error';
  response.status(error.statusCode).json({
    success: false,
    message: error.message,
    // statusCode: error.statusCode,
  });
}

export default ErrorHandlerMiddleware;
