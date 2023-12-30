/* eslint-disable @typescript-eslint/ban-ts-comment */
import { NextFunction, Request, Response, Router } from 'express';
import { convert } from 'libreoffice-convert';
import multer from 'multer';
import path from 'path';
import { AppRoute } from '../../app-route';
import { ENUM_EMAIL_TEMPLATES, IEmailTemplateData, IMailOptions, emailManager, ensureDir, fileUtils } from '../../helpers';
import { ApiResponse } from '../../helpers/api';
import { CohortModel, ICohortDoc, IDayDoc, IDocFile, IKeyFile, IModuleDoc, IPptFile } from '../../models/Cohort';
import { ModuleAssignmentModel } from '../../models/ModuleAssigment';
import { ISessionDoc, SessionModel } from '../../models/Session';
import { IUserDoc, UserModel } from '../../models/User';
import { ProtectAdmin } from '../../passport';

async function convertKeyToPDF(inputFilePath: string, outputFilePath: string) {
  try {
    const buffer = await fileUtils.readFileAsync(inputFilePath);
    return new Promise((resolve, reject) => {
      convert(buffer, 'pptx', undefined, async (err, result) => {
        if (err) {
          reject(err);
        } else {
          try {
            await fileUtils.writeFileAsync(outputFilePath, result);
            resolve(outputFilePath);
          } catch (writeErr) {
            reject(writeErr);
          }
        }
      });
    });
  } catch (readErr) {
    throw readErr;
  }
}

async function convertToPDF(inputFilePath: string, outputFilePath: string): Promise<any> {
  try {
    if (!inputFilePath) throw new Error(`'inputFilePath' is required.`);
    if (!outputFilePath) throw new Error(`'outputFilePath' is required.`);
    const outFileExtenions = path.extname(outputFilePath).slice(1);
    if (!outFileExtenions) throw new Error(`'outFileExtenions' is required.`);
    if (outFileExtenions !== 'pdf') throw new Error(`Invalid output extension provided.`);

    const buffer = await fileUtils.readFileAsync(inputFilePath);
    return new Promise((resolve, reject) => {
      convert(buffer, outFileExtenions, undefined, async (err, result) => {
        if (err) {
          reject(err);
        } else {
          try {
            await fileUtils.writeFileAsync(outputFilePath, result);
            resolve(outputFilePath);
          } catch (writeErr) {
            reject(writeErr);
          }
        }
      });
    });
  } catch (readErr) {
    throw readErr;
  }
}

const pdfStorage = multer.diskStorage({
  destination(request: Request, file: any, cb: any) {
    return cb(null, `${process.cwd()}//cohort/pdf`);
  },
  filename(request: Request, file: any, cb: any) {
    return cb(null, Date.now() + path.extname(file.originalname));
  },
});
const docStorage = multer.diskStorage({
  destination(request: Request, file: any, cb: any) {
    return cb(null, `${process.cwd()}//cohort/doc`);
  },
  filename(request: Request, file: any, cb: any) {
    return cb(null, Date.now() + path.extname(file.originalname));
  },
});
const pptStorage = multer.diskStorage({
  destination(request: Request, file: any, cb: any) {
    return cb(null, `${process.cwd()}//cohort/ppt`);
  },
  filename(request: Request, file: any, cb: any) {
    return cb(null, Date.now() + path.extname(file.originalname));
  },
});

const keyStorage = multer.diskStorage({
  destination(request: Request, file: any, cb: any) {
    return cb(null, `${process.cwd()}//cohort/key`);
  },
  filename(request: Request, file: any, cb: any) {
    return cb(null, Date.now() + path.extname(file.originalname));
  },
});

function pdfFilter(request: Request, file: any, cb: any) {
  const allowedExtensions = ['.pdf'];
  const fileExtension = path.extname(file.originalname).toLowerCase();
  if (allowedExtensions.includes(fileExtension)) {
    cb(null, true);
  } else {
    cb(new Error('Only .pdf files are allowed'));
  }
}
function docFilter(request: Request, file: any, cb: any) {
  const allowedExtensions = ['.doc', '.docx'];
  const fileExtension = path.extname(file.originalname).toLowerCase();
  if (allowedExtensions.includes(fileExtension)) {
    cb(null, true);
  } else {
    cb(new Error('Only .doc or .docx files are allowed'));
  }
}

function pptFilter(request: Request, file: any, cb: any) {
  const allowedExtensions = ['.pptx', '.ppt', '.key'];
  const fileExtension = path.extname(file.originalname).toLowerCase();
  if (allowedExtensions.includes(fileExtension)) {
    cb(null, true);
  } else {
    cb(new Error('Only .ppt or .pptx files are allowed'));
  }
}
function keyFilter(request: Request, file: any, cb: any) {
  const allowedExtensions = ['.key'];
  const fileExtension = path.extname(file.originalname).toLowerCase();
  if (allowedExtensions.includes(fileExtension)) {
    cb(null, true);
  } else {
    cb(new Error('Only .key files are allowed'));
  }
}

const adminCohortController = {
  uploadPdf: multer({ storage: pdfStorage, fileFilter: pdfFilter }).single('pdf'),
  uploadDocx: multer({ storage: docStorage, fileFilter: docFilter }).single('doc'),
  uploadPpt: multer({ storage: pptStorage, fileFilter: pptFilter }).single('ppt'),
  uploadKey: multer({ storage: keyStorage, fileFilter: keyFilter }).single('keyFile'),

  async createCohort(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      let cohort: ICohortDoc | null = await CohortModel.findOne({ name: request.body.name }).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      const session: ISessionDoc | null = await SessionModel.findById(request.body.session);
      if (cohort) {
        return ApiResponse.badRequest(request, response, `Cohort with name '${cohort.name}' already exists.`);
      } else if (!session) {
        return ApiResponse.badRequest(request, response, `Session not found`);
      }
      cohort = await CohortModel.create({
        name: request.body.name,
        year: request.body.year,
        session: session._id,
      });
      cohort = await CohortModel.findById(cohort._id).populate('session');
      return ApiResponse.created(request, response, { cohort, message: 'Cohort created successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async editCohort(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      let cohort: ICohortDoc | null = await CohortModel.findById(request.body.id).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      const session: ISessionDoc | null = await SessionModel.findById(request.body.session);
      if (!session) {
        return ApiResponse.badRequest(request, response, `Session not found.`);
      }
      if (!cohort) {
        return ApiResponse.badRequest(request, response, `Cohort not found.`);
      } else {
        const updateAbles = ['name', 'session', 'year'];
        const set: any = Object.keys(request.body).reduce((paramsArr: any, key: string) => {
          if (updateAbles.includes(key)) {
            //@ts-ignore
            if (key === 'session') paramsArr[key] = session._id.toString();
            else paramsArr[key] = request.body[key].trim();
          }
          return paramsArr;
        }, {});

        if (cohort.name === set.name && cohort.session._id.toString() === set.session && cohort.year === set.year) {
          return ApiResponse.ok(request, response, { cohort }, `Nothing to update .`);
        }
        const cohortExist = await CohortModel.findOne({ name: set.name });
        if (cohortExist) {
          if (request.body.id !== cohortExist._id.toString()) {
            return ApiResponse.badRequest(request, response, `Cohort with name '${set.name}' exists.`);
          } else {
            if (!Object.keys(set).length) {
              return ApiResponse.badRequest(request, response, `No parameters were provided.`);
            } else {
              cohort.updatedAt = new Date();
              cohort = await CohortModel.findOneAndUpdate(
                { _id: cohort._id },
                { $set: set },
                {
                  new: true,
                  upsert: false,
                  populate: [
                    { path: 'session', model: 'Session' },
                    { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
                  ],
                }
              );
              return ApiResponse.created(request, response, { cohort, message: 'Cohort created successfully.' });
            }
          }
        }
        if (!Object.keys(set).length) {
          return ApiResponse.badRequest(request, response, `No parameters were provided.`);
        } else {
          cohort.updatedAt = new Date();
          cohort = await CohortModel.findOneAndUpdate(
            { _id: cohort._id },
            { $set: set },
            {
              new: true,
              upsert: false,
              populate: [
                { path: 'session', model: 'Session' },
                { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
              ],
            }
          );
          return ApiResponse.created(request, response, { cohort, message: 'Cohort created successfully.' });
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async getAllCohorts(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      if (request.query.id) {
        const cohort: ICohortDoc | null = await CohortModel.findById(request.query.id).populate([
          { path: 'session', model: 'Session' },
          { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
        ]);
        if (!cohort) {
          return ApiResponse.notFound(request, response, 'Cohort not found.');
        } else {
          return ApiResponse.ok(request, response, { cohort });
        }
      } else {
        const cohorts: ICohortDoc[] = await CohortModel.find({}).populate([
          { path: 'session', model: 'Session' },
          { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
        ]);
        return ApiResponse.ok(request, response, { cohorts });
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async addModule(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      let cohort: ICohortDoc | null = await CohortModel.findById(request.body.cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      const moduleName: string = request.body.name;
      if (cohort) {
        const existingModuleNames = cohort.modules.map((module) => module.name);
        if (existingModuleNames.includes(moduleName)) {
          return ApiResponse.badRequest(request, response, 'Duplicate module names are not allowed within a cohort.');
        } else {
          let displayOrder: number = 1;
          if (cohort.modules.length) {
            displayOrder = Math.max(...cohort.modules.map((m) => m.displayOrder)) + 1;
          }
          const newModule: any = {
            name: moduleName,
            days: [],
            users: [],
            displayOrder,
          };
          cohort = await CohortModel.findByIdAndUpdate(
            cohort._id,
            { $push: { modules: newModule } },
            {
              new: true,
              upsert: false,
              populate: [
                { path: 'session', model: 'Session' },
                { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
              ],
            }
          ).exec();
          return ApiResponse.ok(request, response, { cohort, message: 'Module added to cohort successfully.' });
        }
      } else {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async editModule(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const name: string = request.body.name;

      let cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);

      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      }

      const moduleExist: IModuleDoc | undefined = cohort.modules.find((m: IModuleDoc) => m._id.toString() === moduleId);
      if (!moduleExist) {
        return ApiResponse.notFound(request, response, 'Module not found.');
      }
      if (cohort.modules.filter((m) => m.name === name)[0]) {
        return ApiResponse.badRequest(request, response, `Module with '${name}' already exists.`);
      }
      moduleExist.name = name;
      moduleExist.updatedAt = new Date();

      cohort = await CohortModel.findByIdAndUpdate(
        cohortId,
        {
          $set: { 'modules.$[module]': moduleExist },
        },
        {
          new: true,
          arrayFilters: [{ 'module._id': moduleId }],
          populate: [
            { path: 'session', model: 'Session' },
            { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
          ],
        }
      ).exec();

      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      }

      return ApiResponse.ok(request, response, { cohort, message: 'Module updated successfully.' });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async reOrderModules(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const { cohortId, modulesOrder } = request.body;

      let cohort: ICohortDoc | null = await CohortModel.findOne({ _id: cohortId });
      if (!cohort) {
        return ApiResponse.badRequest(request, response, `Cohort not found.`);
      }
      cohort.modules = cohort.modules.map((module: IModuleDoc) => {
        module.displayOrder = modulesOrder.filter((order: any) => order.id === module._id.toString())[0].displayOrder;
        return module;
      });

      await CohortModel.findOneAndUpdate({ _id: cohortId }, { $set: { modules: cohort.modules } });
      cohort = await CohortModel.findOne({ _id: cohortId }).populate([
        { path: 'session', model: 'Session' },

        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);

      return ApiResponse.ok(request, response, { cohort });
    } catch (err) {
      console.error(err);

      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async deleteModule(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      let cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);

      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      }

      const moduleExist: IModuleDoc | undefined = cohort.modules.find((m: IModuleDoc) => m._id.toString() === moduleId);

      if (!moduleExist) {
        return ApiResponse.notFound(request, response, 'Module not found.');
      }
      // @ts-ignore
      const moduleAssigned = await ModuleAssignmentModel.find({ module: moduleExist._id });
      if (moduleExist.users.length) {
        return ApiResponse.badRequest(request, response, `Module is already assigned to some clients.`);
      }
      if (moduleExist.days.length) {
        await Promise.all(
          moduleExist.days.map(async (day: IDayDoc) => {
            if (day.pdfs.length) {
              await Promise.all(
                day.pdfs.map(async (pdf: { url: string; title: string }) => {
                  const pdfPath: string = pdf.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
                  await fileUtils.autoUnlink(pdfPath);
                  return pdf;
                })
              );
            }
            if (day.ppts.length) {
              await Promise.all(
                day.ppts.map(async (ppt: IPptFile) => {
                  if ((ppt.viewUrl && process.env.APP_DOMAIN) || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`) {
                    const filePath: string = ppt.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
                    await fileUtils.autoUnlink(filePath);
                  }
                  if ((ppt.downloadUrl && process.env.APP_DOMAIN) || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`) {
                    const filePath: string = ppt.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
                    await fileUtils.autoUnlink(filePath);
                  }
                  return ppt;
                })
              );
            }

            if (day.keys.length) {
              await Promise.all(
                day.keys.map(async (key: IKeyFile) => {
                  if ((key.viewUrl && process.env.APP_DOMAIN) || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`) {
                    const filePath: string = key.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
                    await fileUtils.autoUnlink(filePath);
                  }
                  if ((key.downloadUrl && process.env.APP_DOMAIN) || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`) {
                    const filePath: string = key.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
                    await fileUtils.autoUnlink(filePath);
                  }
                  return key;
                })
              );
            }

            if (day.docs.length) {
              await Promise.all(
                day.docs.map(async (doc: IDocFile) => {
                  if ((doc.viewUrl && process.env.APP_DOMAIN) || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`) {
                    const filePath: string = doc.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
                    await fileUtils.autoUnlink(filePath);
                  }
                  if ((doc.downloadUrl && process.env.APP_DOMAIN) || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`) {
                    const filePath: string = doc.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
                    await fileUtils.autoUnlink(filePath);
                  }
                  return doc;
                })
              );
            }
            return day;
          })
        );
      }

      cohort = await CohortModel.findByIdAndUpdate(
        cohortId,
        { $pull: { modules: { _id: moduleId } } },
        {
          new: true,
          populate: [
            { path: 'session', model: 'Session' },
            { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
          ],
        }
      ).exec();

      //@ts-ignore
      cohort.modules = cohort.modules.map((m) => {
        if (m.displayOrder > moduleExist.displayOrder) {
          m.displayOrder = m.displayOrder - 1;
          return m;
        } else {
          return m;
        }
      });
      //@ts-ignore
      await CohortModel.findByIdAndUpdate({ _id: cohortId }, { $set: { modules: cohort.modules } });

      return ApiResponse.ok(request, response, { cohort }, 'Module deleted from cohort successfully.');
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async addDay(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const title: string = request.body.title;
      let cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      }
      const moduleExist: any = cohort.modules.find((m: IModuleDoc) => m._id.toString() === moduleId);
      if (!moduleExist) {
        return ApiResponse.notFound(request, response, 'Module not found.');
      }

      if (moduleExist.days.length) {
        const dayAlreadyExist = moduleExist.days.find((d: IDayDoc) => d.title === title);
        if (dayAlreadyExist) {
          return ApiResponse.notFound(request, response, `Day already exists with title '${title}'.`);
        }
      }

      const newDay = { title: title }; // Create the new day object
      cohort = await CohortModel.findByIdAndUpdate(
        cohortId,
        {
          $push: { 'modules.$[module].days': newDay },
        },
        {
          new: true, // Return the updated cohort
          arrayFilters: [{ 'module._id': moduleId }],
          populate: [
            { path: 'session', model: 'Session' },
            { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
          ],
        }
      ).exec();

      return ApiResponse.ok(request, response, { cohort }, 'Day added in module successfully.');
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deleteDay(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      let cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      }
      const moduleExist: any = cohort.modules.find((m: IModuleDoc) => m._id.toString() === moduleId);
      if (!moduleExist) {
        return ApiResponse.notFound(request, response, 'Module not found.');
      }
      const dayExist: any = moduleExist.days.find((d: IDayDoc) => d._id.toString() === dayId);
      if (!dayExist) {
        return ApiResponse.notFound(request, response, 'Day not found.');
      }
      if (dayExist.pdfs.length) {
        await Promise.all(
          dayExist.pdfs.map(async (pdf: { url: string; title: string }) => {
            if ((pdf.url && process.env.APP_DOMAIN) || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`) {
              const pdfPath: string = pdf.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
              await fileUtils.autoUnlink(pdfPath);
            }
            return pdf;
          })
        );
      }

      if (dayExist.docs.length) {
        await Promise.all(
          dayExist.docs.map(async (doc: { url: string; title: string }) => {
            if ((doc.url && process.env.APP_DOMAIN) || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`) {
              const pdfPath: string = doc.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
              await fileUtils.autoUnlink(pdfPath);
            }
            return doc;
          })
        );
      }

      cohort = await CohortModel.findByIdAndUpdate(
        cohortId,
        {
          $pull: { 'modules.$[module].days': { _id: dayId } },
        },
        {
          new: true,
          arrayFilters: [{ 'module._id': moduleId }],
          populate: [
            { path: 'session', model: 'Session' },
            { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
          ],
        }
      ).exec();

      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      }

      return ApiResponse.ok(request, response, { cohort }, 'Day deleted from cohort successfully.');
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async deleteCohortById(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohort: ICohortDoc | null = await CohortModel.findById(request.body.id).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, `Cohort not found.`);
      } else {
        await Promise.all(
          cohort.modules.map(async (m: IModuleDoc) => {
            await Promise.all(
              m.days.map(async (d: IDayDoc) => {
                await Promise.all(
                  d.pdfs.map(async (pdf: any) => {
                    await fileUtils.autoUnlink(pdf.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
                    return pdf;
                  })
                );

                await Promise.all(
                  d.docs.map(async (doc: any) => {
                    await fileUtils.autoUnlink(doc.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
                    return doc;
                  })
                );

                await Promise.all(
                  d.ppts.map(async (ppt: any) => {
                    await fileUtils.autoUnlink(ppt.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
                    return ppt;
                  })
                );

                return d;
              })
            );
            return m;
          })
        );
        await CohortModel.deleteOne({ _id: request.body.id });
        // const { acknowledged, deletedCount } = await CohortModel.deleteOne({ _id: request.body.id });
        // if (deletedCount > 0) {
        return ApiResponse.ok(request, response, { id: request.body.id }, `Cohort deleted successfully.`);
        //@ts-ignore

        // } else {
        //   return ApiResponse.notFound(request, response);
        // }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async delete(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohorts: ICohortDoc[] = await CohortModel.find({}).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (cohorts.length) {
        await Promise.all(
          cohorts.map(async (cohort: ICohortDoc) => {
            await Promise.all(
              cohort.modules.map(async (m: IModuleDoc) => {
                await Promise.all(
                  m.days.map(async (d: IDayDoc) => {
                    await Promise.all(
                      d.pdfs.map(async (pdf: any) => {
                        await fileUtils.autoUnlink(pdf.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
                        return pdf;
                      })
                    );

                    await Promise.all(
                      d.docs.map(async (doc: any) => {
                        await fileUtils.autoUnlink(doc.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
                        return doc;
                      })
                    );

                    return d;
                  })
                );
                return m;
              })
            );
          })
        );
      }
      const { acknowledged, deletedCount } = await CohortModel.deleteMany({});
      if (deletedCount > 0) {
        return ApiResponse.noContent(request, response);
      } else {
        return ApiResponse.notFound(request, response);
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async uploadPdfDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const pdfTitle: string = request.body.pdfTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      if (!request.file) {
        return ApiResponse.badRequest(request, response, `'pdf' is required.`);
      }
      if (!cohortId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'cohortId' is required.`);
      }
      if (!moduleId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'moduleId' is required.`);
      }
      if (!dayId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'dayId' is required.`);
      }
      if (!pdfTitle) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'pdfTitle' is required.`);
      }

      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          request.file ? await fileUtils.autoUnlink(request.file.path) : null;
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            // @ts-ignore
            dayExist.pdfs.push({ url: request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`), title: pdfTitle, isDownloadable: isDownloadable });

            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();

            return ApiResponse.ok(request, response, { cohort }, 'Pdf uploaded successfully.');
          }
        }
      }
    } catch (err) {
      request.file ? await fileUtils.autoUnlink(request.file.path) : null;
      return ApiResponse.badRequest(request, response, {});
    }
  },

  async editPdfDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const pdfId: string = request.body.pdfId;
      const pdfTitle: string = request.body.pdfTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      if (!request.file) {
        return ApiResponse.badRequest(request, response, `'pdf' is required.`);
      }
      if (!cohortId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'cohortId' is required.`);
      }
      if (!moduleId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'moduleId' is required.`);
      }
      if (!dayId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'dayId' is required.`);
      }
      if (!pdfTitle) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'pdfTitle' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          request.file ? await fileUtils.autoUnlink(request.file.path) : null;
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            const pdfExist = dayExist.pdfs.filter((p) => p._id.toString() === pdfId)[0];
            if (!pdfExist) {
              request.file ? await fileUtils.autoUnlink(request.file.path) : null;
              return ApiResponse.notFound(request, response, `Pdf not found.`);
            } else {
              await fileUtils.autoUnlink(pdfExist.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));

              pdfExist.url = request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`);
              pdfExist.title = pdfTitle;
              pdfExist.isDownloadable = isDownloadable;
              pdfExist.updatedAt = new Date();
              dayExist.pdfs = dayExist.pdfs.map((p) => {
                if (p._id.toString() === pdfId) return pdfExist;
                else return p;
              });

              moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
                if (d._id.toString() === dayId) return dayExist;
                else return d;
              });
              cohort.modules.map((m: IModuleDoc) => {
                if (m._id.toString() === moduleId) {
                  return moduleExist;
                } else {
                  return m;
                }
              });
            }

            await cohort.save();

            return ApiResponse.ok(request, response, { cohort }, 'Pdf updated successfully.');
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async deletePdfDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const pdfId: string = request.body.pdfId;
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            const pdfExist = dayExist.pdfs.filter((doc) => doc._id.toString() === pdfId)[0];
            if (!pdfExist) {
              return ApiResponse.notFound(request, response, `Pdf not found.`);
            }

            const pdfPath = pdfExist.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
            await fileUtils.autoUnlink(pdfPath);
            // @ts-ignore
            dayExist.pdfs.pull(pdfId);

            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();

            return ApiResponse.ok(request, response, { cohort }, 'Pdf deleted successfully.');
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  /** TODO : to be completed */
  async editDay(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const title: string = request.body.title;

      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.notFound(request, response, 'Module not found.');
        } else {
          const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
          if (!moduleExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Module not found.`);
          } else {
            const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
            if (!dayExist) {
              request.file ? await fileUtils.autoUnlink(request.file.path) : null;
              return ApiResponse.notFound(request, response, `Day not found.`);
            } else {
              dayExist.title = title;
              dayExist.updatedAt = new Date();

              moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
                if (d._id.toString() === dayId) {
                  return dayExist;
                } else {
                  return d;
                }
              });
              cohort.modules.map((m: IModuleDoc) => {
                if (m._id.toString() === moduleId) {
                  return moduleExist;
                } else {
                  return m;
                }
              });

              await cohort.save();

              return ApiResponse.ok(request, response, { cohort }, 'Day updated successfully.');
            }
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async uploadDocxDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const docTitle: string = request.body.docTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      if (!request.file) {
        return ApiResponse.badRequest(request, response, `'doc' is required.`);
      }
      if (!cohortId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'cohortId' is required.`);
      }
      if (!moduleId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'moduleId' is required.`);
      }
      if (!dayId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'dayId' is required.`);
      }
      if (!docTitle) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'docTitle' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId)
        .populate([
          { path: 'session', model: 'Session' },
          { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
        ])
        .exec();
      if (!cohort) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          request.file ? await fileUtils.autoUnlink(request.file.path) : null;
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            // @ts-ignore
            const outputFilePath: string = request.file.path.replace(/\.[^.]+$/, '.pdf');
            const cloneFilePath: string = await convertToPDF(request.file.path, outputFilePath);
            // @ts-ignore
            dayExist.docs.push({
              viewUrl: cloneFilePath.replace(`${process.cwd()}`, process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`),
              downloadUrl: request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`),
              title: docTitle,
              isDownloadable,
            });

            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();

            return ApiResponse.ok(request, response, { cohort }, 'Pdf uploaded successfully.');
          }
        }
      }
    } catch (err) {
      request.file ? await fileUtils.autoUnlink(request.file.path) : null;
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  async editDocxDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const docId: string = request.body.docId;
      const docTitle: string = request.body.docTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      if (!request.file) {
        return ApiResponse.badRequest(request, response, `'doc' is required.`);
      }
      if (!cohortId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'cohortId' is required.`);
      }
      if (!moduleId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'moduleId' is required.`);
      }
      if (!dayId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'dayId' is required.`);
      }
      if (!docTitle) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'docTitle' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          request.file ? await fileUtils.autoUnlink(request.file.path) : null;
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            const docExist = dayExist.docs.filter((p: any) => p._id.toString() === docId)[0];
            if (!docExist) {
              request.file ? await fileUtils.autoUnlink(request.file.path) : null;
              return ApiResponse.notFound(request, response, `Ppt not found.`);
            } else {
              await fileUtils.autoUnlink(docExist.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
              await fileUtils.autoUnlink(docExist.downloadUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
              const outputFilePath: string = request.file.path.replace(/\.[^.]+$/, '.pdf');
              const cloneFilePath: string = await convertToPDF(request.file.path, outputFilePath);
              //@ts-ignore
              docExist.viewUrl = cloneFilePath.replace(`${process.cwd()}`, process.env.APP_DOMAIN);
              docExist.title = docTitle;
              //@ts-ignore
              docExist.downloadUrl = request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN);
              docExist.isDownloadable = isDownloadable;
              docExist.updatedAt = new Date();
              dayExist.docs = dayExist.docs.map((docFile: IDocFile) => {
                //@ts-ignore
                if (docFile._id.toString() === docId) return docExist;
                else return docFile;
              });
              moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
                if (d._id.toString() === dayId) {
                  return dayExist;
                } else {
                  return d;
                }
              });
              cohort.modules.map((m: IModuleDoc) => {
                if (m._id.toString() === moduleId) {
                  return moduleExist;
                } else {
                  return m;
                }
              });
            }

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort }, 'Pdf updated successfully.');
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deleteDocxDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const docId: string = request.body.docId;
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            //@ts-ignore
            const docExist = dayExist.docs.filter((docFile) => docFile._id.toString() === docId)[0];
            if (!docExist) {
              return ApiResponse.notFound(request, response, `Doc not found.`);
            }
            const pdfPath: string = docExist.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
            const docFilePath: string = docExist.downloadUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
            await fileUtils.autoUnlink(pdfPath);
            await fileUtils.autoUnlink(docFilePath);
            // @ts-ignore
            dayExist.docs.pull(docId);
            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort }, 'Doc deleted successfully.');
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async uploadPptDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const pptTitle: string = request.body.pptTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      if (!request.file) {
        return ApiResponse.badRequest(request, response, `'doc' is required.`);
      }
      if (!cohortId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'cohortId' is required.`);
      }
      if (!moduleId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'moduleId' is required.`);
      }
      if (!dayId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'dayId' is required.`);
      }
      if (!pptTitle) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'pptTitle' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          request.file ? await fileUtils.autoUnlink(request.file.path) : null;
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            // @ts-ignore
            const outputFilePath: string = request.file.path.replace(/\.[^.]+$/, '.pdf');
            const cloneFilePath: string = await convertToPDF(request.file.path, outputFilePath);
            // @ts-ignore
            dayExist.ppts.push({
              viewUrl: cloneFilePath.replace(`${process.cwd()}`, process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`),
              downloadUrl: request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`),
              title: pptTitle,
              isDownloadable,
            });

            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort, message: 'Ppt uploaded successfully.' });
          }
        }
      }
    } catch (err) {
      request.file ? await fileUtils.autoUnlink(request.file.path) : null;
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async editPptDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const pptId: string = request.body.pptId;
      const pptTitle: string = request.body.pptTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      if (!request.file) {
        return ApiResponse.badRequest(request, response, `'doc' is required.`);
      }
      if (!cohortId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'cohortId' is required.`);
      }
      if (!moduleId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'moduleId' is required.`);
      }
      if (!dayId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'dayId' is required.`);
      }
      if (!pptTitle) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'pptTitle' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          request.file ? await fileUtils.autoUnlink(request.file.path) : null;
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            const pptExist = dayExist.ppts.filter((p: any) => p._id.toString() === pptId)[0];
            if (!pptExist) {
              request.file ? await fileUtils.autoUnlink(request.file.path) : null;
              return ApiResponse.notFound(request, response, `Ppt not found.`);
            } else {
              await fileUtils.autoUnlink(pptExist.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
              await fileUtils.autoUnlink(pptExist.downloadUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
              const outputFilePath: string = request.file.path.replace(/\.[^.]+$/, '.pdf');
              const cloneFilePath: string = await convertToPDF(request.file.path, outputFilePath);
              //@ts-ignore
              pptExist.viewUrl = cloneFilePath.replace(`${process.cwd()}`, process.env.APP_DOMAIN);
              pptExist.title = pptTitle;
              //@ts-ignore
              pptExist.downloadUrl = request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN);
              pptExist.isDownloadable = isDownloadable;
              pptExist.updatedAt = new Date();
              dayExist.ppts = dayExist.ppts.map((pptFile: IDocFile) => {
                //@ts-ignore
                if (pptFile._id.toString() === pptId) return pptExist;
                else return pptFile;
              });
              moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
                if (d._id.toString() === dayId) {
                  return dayExist;
                } else {
                  return d;
                }
              });
              cohort.modules.map((m: IModuleDoc) => {
                if (m._id.toString() === moduleId) {
                  return moduleExist;
                } else {
                  return m;
                }
              });
            }

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort, message: 'Pdf updated successfully.' });
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deletePptDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const pptId: string = request.body.pptId;
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            const pptExist = dayExist.ppts.filter((ppt: any) => ppt._id.toString() === pptId)[0];
            if (!pptExist) {
              return ApiResponse.notFound(request, response, `Ppt not found.`);
            }

            const pptPath = pptExist.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
            const keyFilePath = pptExist.downloadUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
            await fileUtils.autoUnlink(pptPath);
            await fileUtils.autoUnlink(keyFilePath);
            // @ts-ignore
            dayExist.ppts.pull(pptId);
            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort, message: 'Ppt deleted successfully.' });
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async uploadKeyDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const keyTitle: string = request.body.keyTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      if (!request.file) {
        return ApiResponse.badRequest(request, response, `'key file' is required.`);
      }
      if (!cohortId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'cohortId' is required.`);
      }
      if (!moduleId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'moduleId' is required.`);
      }
      if (!dayId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'dayId' is required.`);
      }
      if (!keyTitle) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'keyTitle' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          request.file ? await fileUtils.autoUnlink(request.file.path) : null;
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            // const keyFileContent = await fileUtils.readFileAsync(request.file.path);
            const cloneFilePath = request.file.path.replace('.key', '.pptx');
            await convertKeyToPDF(request.file.path, cloneFilePath);
            // await fileUtils.writeFileAsync(cloneFilePath, keyFileContent);
            // @ts-ignore
            dayExist.keys.push({
              viewUrl: cloneFilePath.replace(`${process.cwd()}`, process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`),
              downloadUrl: request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`),
              title: keyTitle,
              isDownloadable,
            });

            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort, message: 'Key file uploaded successfully.' });
          }
        }
      }
    } catch (err) {
      request.file ? await fileUtils.autoUnlink(request.file.path) : null;
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async editKeyDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const keyId: string = request.body.keyId;
      const keyTitle: string = request.body.keyTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      if (!request.file) {
        return ApiResponse.badRequest(request, response, `'keyFile' is required.`);
      }
      if (!cohortId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'cohortId' is required.`);
      }
      if (!moduleId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'moduleId' is required.`);
      }
      if (!dayId) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'dayId' is required.`);
      }
      if (!keyTitle) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.badRequest(request, response, `'keyTitle' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        request.file ? await fileUtils.autoUnlink(request.file.path) : null;
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          request.file ? await fileUtils.autoUnlink(request.file.path) : null;
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            request.file ? await fileUtils.autoUnlink(request.file.path) : null;
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            //@ts-ignore
            const keyExist = dayExist.keys.filter((keyFile) => keyFile._id.toString() === keyId)[0];

            if (!keyExist) {
              request.file ? await fileUtils.autoUnlink(request.file.path) : null;
              return ApiResponse.notFound(request, response, `Ppt not found.`);
            } else {
              await fileUtils.autoUnlink(keyExist.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
              await fileUtils.autoUnlink(keyExist.downloadUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd()));
              const cloneFilePath = request.file.path.replace('.key', '.pptx');
              await convertKeyToPDF(request.file.path, cloneFilePath);
              //@ts-ignore
              keyExist.viewUrl = cloneFilePath.replace(`${process.cwd()}`, process.env.APP_DOMAIN);
              keyExist.title = keyTitle;
              //@ts-ignore
              keyExist.downloadUrl = request.file.path.replace(`${process.cwd()}`, process.env.APP_DOMAIN);
              keyExist.isDownloadable = isDownloadable;
              keyExist.updatedAt = new Date();
              dayExist.keys = dayExist.keys.map((keyFile: IKeyFile) => {
                //@ts-ignore
                if (keyFile._id.toString() === keyId) return keyExist;
                else return keyFile;
              });
              moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
                if (d._id.toString() === dayId) {
                  return dayExist;
                } else {
                  return d;
                }
              });
              cohort.modules.map((m: IModuleDoc) => {
                if (m._id.toString() === moduleId) {
                  return moduleExist;
                } else {
                  return m;
                }
              });
            }

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort, message: 'Key file updated successfully.' });
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deleteKeyDocument(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const keyFileId: string = request.body.keyFileId;
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            //@ts-ignore
            const keyFileExist = dayExist.keys.filter((keyFile) => keyFile._id.toString() === keyFileId)[0];
            if (!keyFileExist) {
              return ApiResponse.notFound(request, response, `Ppt not found.`);
            }
            const pptPath = keyFileExist.viewUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
            const keyFilePath = keyFileExist.downloadUrl.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
            await fileUtils.autoUnlink(pptPath);
            await fileUtils.autoUnlink(keyFilePath);
            // @ts-ignore
            dayExist.keys.pull(keyFileId);
            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort, message: 'Key file deleted successfully.' });
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async assignModuleToUser(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const userId: string = request.body.userId;
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      const user: IUserDoc | null = await UserModel.findOne({ _id: userId, role: 'user' });
      if (!cohort) return ApiResponse.badRequest(request, response, 'Cohort not found.');
      if (!user) return ApiResponse.badRequest(request, response, 'User not found.');
      else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.badRequest(request, response, 'Module not found.');
        } else {
          const userAlreadyAssigned = moduleExist.users.filter((u) => u === userId || u.toString() === userId)[0];
          if (userAlreadyAssigned) {
            return ApiResponse.badRequest(request, response, 'User already assigned to module');
          }

          moduleExist.users.push(userId);
          // @ts-ignore
          await ModuleAssignmentModel.create({ assignedBy: request.user._id, module: moduleId, user: userId, comments: `Module '${moduleExist.name}' is assigned to user '${user.name} by admin '${request.user.name}'` });
          cohort.modules.map((m: IModuleDoc) => {
            if (m._id) {
              if (m._id.toString() === moduleId) return moduleExist;
              else return m;
            } else return m;
          });

          await cohort.save();
          const templateOptons: IEmailTemplateData = {
            recipient: user.email,
            template: ENUM_EMAIL_TEMPLATES.CLIENT_MODULE_ASSIGNED,
            info: { user, module: moduleExist },
          };
          const mailOptions: IMailOptions = emailManager.getEmailTemplate(templateOptons);
          // await emailManager.send(mailOptions);
          return ApiResponse.ok(request, response, { cohort, message: 'Module assigned to user successfully.' });
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async assignMultipleModuleToUser(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      let assignableModules: any[] = request.body.assignableModules;
      const userId: string = request.body.userId;
      const user: IUserDoc | null = await UserModel.findOne({ _id: userId, role: 'user' });

      if (!user) return ApiResponse.badRequest(request, response, 'User not found.');
      assignableModules = await Promise.all(
        assignableModules.map(async (assignable: { cohortId: string; moduleId: string }) => {
          const { cohortId, moduleId } = assignable;
          const alreadyAssigned = await ModuleAssignmentModel.findOne({ user: userId, module: moduleId });
          if (alreadyAssigned) return { ...assignable, message: 'Module already assigned.', success: false };
          else {
            const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
              { path: 'session', model: 'Session' },
              { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
            ]);
            if (!cohort) {
              return { ...assignable, message: 'Cohort not found.', success: false };
            }
            const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m) => m._id.toString() === moduleId)[0];
            if (!moduleExist) {
              return { ...assignable, message: 'Module not found.', success: false };
            }

            // @ts-ignore
            await ModuleAssignmentModel.create({ assignedBy: request.user._id, module: moduleId, user: userId, comments: `Module '${moduleExist.name}' is assigned to user '${user.name} by admin '${request.user.name}'` });
            await CohortModel.findOneAndUpdate({ 'modules._id': moduleExist._id }, { $push: { 'modules.$.users': userId } }, { new: true });
            const templateOptons: IEmailTemplateData = {
              recipient: user.email,
              template: ENUM_EMAIL_TEMPLATES.CLIENT_MODULE_ASSIGNED,
              info: { user, module: moduleExist },
            };
            const mailOptions: IMailOptions = emailManager.getEmailTemplate(templateOptons);
            // await emailManager.send(mailOptions);
            return { ...assignable, message: 'Module assigned successfully.', success: true };
          }
        })
      );
      const assignedModules = assignableModules.filter((i) => i.success);
      const unAssignedModules = assignableModules.filter((i) => !i.success);

      return ApiResponse.ok(request, response, { assignedModules, unAssignedModules, message: `${assignedModules.length} module assigned to user.` });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async unAssignModuleToUser(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const userId: string = request.body.userId;
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      const user: IUserDoc | null = await UserModel.findOne({ _id: userId, role: 'user' });
      if (!cohort) return ApiResponse.badRequest(request, response, 'Cohort not found.');
      if (!user) return ApiResponse.badRequest(request, response, 'User not found.');
      else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.badRequest(request, response, 'Module not found.');
        } else {
          const isModuleAssignedToUser = moduleExist.users.filter((u) => u._id.toString() === userId)[0];
          if (!isModuleAssignedToUser) {
            return ApiResponse.badRequest(request, response, 'User is not assigned to module.');
          }
          // @ts-ignore
          moduleExist.users.pull(userId);
          await ModuleAssignmentModel.findOneAndDelete({ user: userId, module: moduleId });
          cohort.modules.map((m: IModuleDoc) => {
            if (m._id) {
              if (m._id.toString() === moduleId) return moduleExist;
              else return m;
            } else return m;
          });

          await cohort.save();
          const templateOptons: IEmailTemplateData = {
            recipient: user.email,
            template: ENUM_EMAIL_TEMPLATES.CLIENT_MODULE_UNASSIGNED,
            info: { user, module: moduleExist },
          };
          const mailOptions: IMailOptions = emailManager.getEmailTemplate(templateOptons);
          // await emailManager.send(mailOptions);

          return ApiResponse.ok(request, response, { cohort, message: 'Module unassigned to user successfully.' });
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async uploadVimeoVideo(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const vimeoUrl: string = request.body.vimeoUrl;
      const vimeoTitle: string = request.body.vimeoTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            // @ts-ignore
            dayExist.videos.push({ url: vimeoUrl, title: vimeoTitle, isDownloadable });

            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort, message: 'Vimeo Video uploaded successfully.' });
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async editVimeoVideo(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const vimeoId: string = request.body.vimeoId;
      const vimeoUrl: string = request.body.vimeoUrl;
      const vimeoTitle: string = request.body.vimeoTitle;
      const isDownloadable: boolean = request.body.isDownloadable;
      if (isDownloadable === null || isDownloadable === undefined) {
        return ApiResponse.badRequest(request, response, `'isDownloadable' is required.`);
      }
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            const videoExist = dayExist.videos.filter((v) => v._id.toString() === vimeoId)[0];

            if (!videoExist) {
              return ApiResponse.notFound(request, response, `Video not found.`);
            } else {
              videoExist.url = vimeoUrl;
              videoExist.title = vimeoTitle;
              videoExist.isDownloadable = isDownloadable;
              videoExist.updatedAt = new Date();
              dayExist.videos = dayExist.videos.map((v) => {
                if (v._id.toString() === vimeoId) return videoExist;
                else return v;
              });
              moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
                if (d._id.toString() === dayId) {
                  return dayExist;
                } else {
                  return d;
                }
              });
              cohort.modules.map((m: IModuleDoc) => {
                if (m._id.toString() === moduleId) {
                  return moduleExist;
                } else {
                  return m;
                }
              });

              await cohort.save();
              return ApiResponse.ok(request, response, { cohort, message: 'Vimeo Video updated successfully.' });
            }
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  async deleteVimeoVideo(request: Request, response: Response, next: NextFunction): Promise<any> {
    try {
      const cohortId: string = request.body.cohortId;
      const moduleId: string = request.body.moduleId;
      const dayId: string = request.body.dayId;
      const vimeoId: string = request.body.vimeoId;
      const cohort: ICohortDoc | null = await CohortModel.findById(cohortId).populate([
        { path: 'session', model: 'Session' },
        { path: 'modules.users', model: 'User', select: 'name phoneNo email' },
      ]);
      if (!cohort) {
        return ApiResponse.notFound(request, response, 'Cohort not found.');
      } else {
        const moduleExist: IModuleDoc | undefined = cohort.modules.filter((m: IModuleDoc) => m._id.toString() === moduleId)[0];
        if (!moduleExist) {
          return ApiResponse.notFound(request, response, `Module not found.`);
        } else {
          const dayExist: IDayDoc | undefined = moduleExist.days.filter((d: IDayDoc) => d._id.toString() === dayId)[0];
          if (!dayExist) {
            return ApiResponse.notFound(request, response, `Day not found.`);
          } else {
            const docExist = dayExist.videos.filter((v) => v._id.toString() === vimeoId)[0];
            if (!docExist) {
              return ApiResponse.notFound(request, response, `Vimeo Video not found.`);
            }

            const docPath = docExist.url.replace(process.env.APP_DOMAIN || `https://${process.env.NEXT_PUBLIC_API_HOST}/api/v1`, process.cwd());
            await fileUtils.autoUnlink(docPath);
            // @ts-ignore
            dayExist.videos.pull(vimeoId);

            moduleExist.days = moduleExist.days.map((d: IDayDoc) => {
              if (d._id.toString() === dayId) {
                return dayExist;
              } else {
                return d;
              }
            });
            cohort.modules.map((m: IModuleDoc) => {
              if (m._id.toString() === moduleId) {
                return moduleExist;
              } else {
                return m;
              }
            });

            await cohort.save();
            return ApiResponse.ok(request, response, { cohort, message: 'Vimeo video deleted successfully.' });
          }
        }
      }
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
};

export default adminCohortController;
