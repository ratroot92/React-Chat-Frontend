import { NextFunction, Request, Response } from 'express';

interface IOptions {
  requestDelayTimeout: number;
}

const ReqLogMiddleware = (options: IOptions) => {
  return async function (req: Request, res: Response, next: NextFunction) {
    if (options.requestDelayTimeout) {
      await new Promise((r) => setTimeout(r, options.requestDelayTimeout || 1000));
    }
    return next();
  };
};

export { ReqLogMiddleware };
