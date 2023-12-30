export interface ICustomErrorHandler {
  message: string;
  statusCode: number;
}

export default class CustomErrorHandler extends Error implements ICustomErrorHandler {
  statusCode: number;

  constructor(message: string, statusCode: number) {
    super(message);
    this.statusCode = statusCode || 500;
    this.message = message || 'Internal Server Error';

    Error.captureStackTrace(this, this.constructor);
  }
}
