/* eslint-disable @typescript-eslint/ban-ts-comment */
/* eslint-disable security/detect-object-injection */
'use strict';

import { NextFunction, Request, Response } from 'express';
import mongoose from 'mongoose';
const _hasOwnProperty = Object.prototype.hasOwnProperty;

const Status = {
  OK: 200,
  NO_CONTENT: 204,
  BAD_REQUEST: 400,
  UNPROCESSIBLE_CONTENT: 422,
  UNAUTHORIZED: 401,
  FORBIDDEN: 403,
  NOT_FOUND: 404,
  UNSUPPORTED_ACTION: 405,
  VALIDATION_FAILED: 422,
  SERVER_ERROR: 500,
  CREATED: 201,
  CONFLICT: 409,
};

function statusMessage(status: number): string {
  switch (status) {
    case Status.BAD_REQUEST:
      return 'Bad Request';

    case Status.UNPROCESSIBLE_CONTENT:
      return 'Unprocessable Content';
    case Status.UNAUTHORIZED:
      return 'Unauthorized';
    case Status.FORBIDDEN:
      return 'Forbidden';
    case Status.NOT_FOUND:
      return 'Not Found';
    case Status.UNSUPPORTED_ACTION:
      return 'Unsupported Action';
    case Status.VALIDATION_FAILED:
      return 'Validation Failed';
    case Status.SERVER_ERROR:
      return 'Internal Server Error';
    case Status.CREATED:
      return 'Created';
    case Status.CONFLICT:
      return 'Conflict';
    case Status.OK:
      return 'Success';
    case Status.NO_CONTENT:
      return 'No Content.';

    default:
      throw new Error('Unkown case.');
  }
}

function jsonResponse(res: Response, body: any, options: any) {
  options = options || {};
  options.status = options.status || Status.OK;
  res.status(options.status).json(body || null);
}

const ApiResponse = {
  ok(req: Request, res: Response, data: any, message?: string) {
    const body = {
      message: message || statusMessage(Status.OK),
      success: true,
      data,
    };
    jsonResponse(res, body, {
      status: Status.OK,
    });
  },

  unProcessibleContent(req: Request, res: Response, errors: any) {
    errors = Array.isArray(errors) ? errors : [errors];
    const body = {
      message: statusMessage(Status.UNPROCESSIBLE_CONTENT),
      errors,
    };

    jsonResponse(res, body, {
      status: Status.BAD_REQUEST,
    });
  },
  badRequest(req: Request, res: Response, errors: any) {
    errors = Array.isArray(errors) ? errors : [errors];

    const body = {
      message: statusMessage(Status.BAD_REQUEST),
      errors,
    };

    jsonResponse(res, body, {
      status: Status.BAD_REQUEST,
    });
  },

  unauthorized(req: Request, res: Response) {
    const body = {
      message: statusMessage(Status.UNAUTHORIZED),
    };

    jsonResponse(res, body, {
      status: Status.UNAUTHORIZED,
    });
  },

  forbidden(req: Request, res: Response) {
    const body = {
      message: statusMessage(Status.FORBIDDEN),
    };

    jsonResponse(res, body, {
      status: Status.FORBIDDEN,
    });
  },
  conflict(req: Request, res: Response) {
    const body = {
      message: statusMessage(Status.CONFLICT),
      success: false,
      status: Status.CONFLICT,
    };

    jsonResponse(res, body, {
      status: Status.CONFLICT,
    });
  },
  notFound(req: Request, res: Response, message?: string) {
    const body = {
      message: message || statusMessage(Status.NOT_FOUND),
      success: false,
      status: Status.NOT_FOUND,
    };

    jsonResponse(res, body, {
      status: Status.NOT_FOUND,
    });
  },
  noContent(req: Request, res: Response) {
    const body = {
      message: statusMessage(Status.NO_CONTENT),
      success: true,
      status: Status.NO_CONTENT,
    };

    jsonResponse(res, body, {
      status: Status.NOT_FOUND,
    });
  },

  unsupportedAction(req: Request, res: Response) {
    const body = {
      message: statusMessage(Status.UNSUPPORTED_ACTION),
    };

    jsonResponse(res, body, {
      status: Status.UNSUPPORTED_ACTION,
    });
  },

  invalid(req: Request, res: Response, errors: any) {
    errors = Array.isArray(errors) ? errors : [errors];

    const body = {
      message: statusMessage(Status.VALIDATION_FAILED),
      errors,
    };

    jsonResponse(res, body, {
      status: Status.VALIDATION_FAILED,
    });
  },
  internalServerError(req: Request, res: Response, err: any) {
    if (process.env.APP_DOMAIN === 'development') {
    }
    let message = null;
    const errors: string[] = [];

    if (err.name === 'MulterError' && err.code === 'LIMIT_UNEXPECTED_FILE' && err.message === 'Unexpected field') {
      message = 'Unexpected field.';
    } else if (err.name === 'CastError') {
      message = `Invalid ${err.path} '${err.stringValue}'`;
    }

    // else if (err.name === 'ValidationError') {
    //   // Object.keys(err.errors).map((errorName) => {
    //   //   console.log('=================================');
    //   //   Object.keys(err.errors[errorName]).forEach((errProperty) => {
    //   //     console.log(errProperty, ' ==> ', err.errors[errorName][errProperty]);
    //   //   });
    //     console.log(typeof err.errors[Object.keys(err.errors)[0]]['reason']);
    //     // console.log('err.errors[errorName]', err.errors[errorName]);

    //     if (err.errors[errorName].properties) {
    //       errors.push(err.errors[errorName].properties.message.replace('Path', '').replace(/`/g, '').trim());
    //     }
    //   });
    //   //@ts-ignore
    //   message = err.errors[Object.keys(err.errors)[0]].properties.message.replace('Path', '').replace(/`/g, '').trim();

    //   // console.log(`${err.errors}`);
    // }
    if (err instanceof Error) {
      err = {
        message: message ? message : err.message,
        stacktrace: err.stack,
        errors,
      };
    }
    const body = {
      message: statusMessage(Status.SERVER_ERROR),
      err,
    };

    jsonResponse(res, body, {
      status: Status.SERVER_ERROR,
    });
  },

  requireParams(req: Request, res: Response, parameters: any, next: NextFunction) {
    const missing: any[] = [];
    parameters = Array.isArray(parameters) ? parameters : [parameters];
    parameters.forEach((parameter: any) => {
      if (!(req.body && _hasOwnProperty.call(req.body, parameter)) && !(req.params && _hasOwnProperty.call(req.params, parameter)) && !_hasOwnProperty.call(req.query, parameter)) {
        missing.push(`Missing required parameter: ${parameter}`);
      }
    });
    if (missing.length) {
      return ApiResponse.badRequest(req, res, missing);
    } else {
      return next();
    }
  },
  requireParamId(req: Request, res: Response, next: NextFunction) {
    if (req.params.id === ':id' || !req.params.id) {
      return ApiResponse.badRequest(req, res, `Param 'id' is required .`);
    } else if (!mongoose.Types.ObjectId.isValid(req.params.id)) {
      return ApiResponse.badRequest(req, res, `Param 'id' is invalid .`);
    } else {
      return next();
    }
  },
  created(req: Request, res: Response, data: any) {
    jsonResponse(res, data, {
      status: Status.OK,
      success: true,
    });
  },

  requireHeaders(req: Request, res: Response, headers: any, next: NextFunction) {
    const missing: any[] = [];

    headers = Array.isArray(headers) ? headers : [headers];

    headers.forEach((header: any) => {
      if (!(req.headers && _hasOwnProperty.call(req.headers, header))) {
        missing.push(`Missing required header parameter: ${header}`);
      }
    });

    if (missing.length) {
      ApiResponse.badRequest(req, res, missing);
    } else {
      next();
    }
  },
};

export { ApiResponse };
