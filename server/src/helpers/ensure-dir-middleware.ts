import { Response, Request, NextFunction } from 'express';
import { fileUtils } from './file-utils';

function ensureDir(dirPath: string) {
  return async (request: Request, response: Response, next: NextFunction) => {
    if (!dirPath) {
      throw new Error(`'dirPath is required.'`);
    } else {
      const dirExist = await fileUtils.existAsync(dirPath);
      if (dirExist === false) {
        // @ts-ignore
        const createDir = await fileUtils.mkdirAsync(dirPath, { recursive: true, mode: 0o777 });
      }
    }
    return next();
  };
}

export { ensureDir };
