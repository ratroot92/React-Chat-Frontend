/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Request, Response } from 'express';
import fs from 'fs';
import path from 'path';
import util from 'util';

import { exec } from 'child_process';
import { ApiResponse } from '../helpers';
import { CohortModel, ICohortDoc, IModuleDoc } from '../models/Cohort';
const promisifiedExec = util.promisify(exec);

const scriptController = {
  dumpDatabaseScript: async (dbDumpName: string): Promise<any> => {
    try {
      if (!dbDumpName) throw new Error(`'dbDumpName' is required.`);
      const databaseName = process.env.MONGO_DB_NAME;
      const dbHost = process.env.MONGO_HOST;
      const dbPort = process.env.MONGO_PORT;
      const dumpDirectory = process.cwd() + '/db_dump/' + dbDumpName;
      const connectionString = `mongodb://${dbHost}:${dbPort}/${databaseName}`;
      const command = `mongodump --uri="${connectionString}" --out ${dumpDirectory}`;
      let logs = '';
      const { stdout, stderr } = await promisifiedExec(command);
      if (stderr) {
        logs += stderr;
        console.error(`Error: ${stderr}`);
        return { isDumped: false, logs };
      }
      return { isDumped: true, logs };
    } catch (error) {
      console.error(`Unexpected error: ${error}`);
      return false;
    }
  },

  restoreDatabaseScript: async (dbDumpName: string): Promise<any> => {
    try {
      if (!dbDumpName) throw new Error(`'dbDumpName' is required.`);
      const databaseName = process.env.MONGO_DB_NAME;
      const restoreDbName = 'ish-temp';
      const dbHost = process.env.MONGO_HOST;
      const dbPort = process.env.MONGO_PORT;
      const dumpDirectory = process.cwd() + '/db_dump/' + dbDumpName + '/' + databaseName;
      const connectionString = `mongodb://${dbHost}:${dbPort}/${restoreDbName}`;
      const command = `mongorestore --uri="${connectionString}" --drop ${dumpDirectory}`;
      let logs = '';
      const { stdout, stderr } = await promisifiedExec(command);
      if (stderr) {
        logs += stderr;
        console.error(`Error: ${stderr}`);
        return { isDumped: false, logs };
      }
      return { isDumped: true, logs };
    } catch (error) {
      console.error(`Unexpected error: ${error}`);
      return false;
    }
  },

  getFolderNames(directoryPath: string): string[] {
    try {
      const folderNames = fs.readdirSync(directoryPath).filter((file) => fs.statSync(path.join(directoryPath, file)).isDirectory());
      return folderNames;
    } catch (error) {
      console.error(`Error reading directory: ${error}`);
      return [];
    }
  },
  getFormattedDate(): string {
    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0');
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Note: Months are zero-based
    const year = currentDate.getFullYear();
    const formattedDate = `${day}_${month}_${year}`;
    return formattedDate;
  },
  executeScript: async function (request: Request, response: Response) {
    try {
      const scriptExecteSecret = process.env.SCRIPT_EXEC_SECRET || 'secret';
      const { scriptName, secret } = request.body;
      if (!secret) {
        return ApiResponse.badRequest(request, response, `secret is required.`);
      }
      if (secret !== scriptExecteSecret) {
        return ApiResponse.badRequest(request, response, `Invalid secret provided.`);
      }
      if (!scriptName) {
        return ApiResponse.badRequest(request, response, `scriptName is required.`);
      }
      switch (scriptName) {
        case 'dumpDatabase': {
          const dumpDbDir = process.env.DUMP_DB_DIR || '/db_dump/';
          const dumpDbPath = process.cwd() + dumpDbDir;
          let { dbDumpName } = request.body;
          if (!dbDumpName) {
            return ApiResponse.badRequest(request, response, `'dbDumpName' is required.`);
          }
          const folderNames = scriptController.getFolderNames(dumpDbPath);
          dbDumpName = dbDumpName + '_' + scriptController.getFormattedDate();
          if (folderNames.includes(dbDumpName)) {
            return ApiResponse.badRequest(request, response, `'dbDumpName' is already used.`);
          }
          const isDumped = await scriptController.dumpDatabaseScript(dbDumpName);
          return ApiResponse.ok(request, response, { isDumped });
        }

        case 'restoreDatabase': {
          const dumpDbDir = process.env.DUMP_DB_DIR || '/db_dump/';
          const dumpDbPath = process.cwd() + dumpDbDir;
          let { dbDumpName } = request.body;
          if (!dbDumpName) {
            return ApiResponse.badRequest(request, response, `'dbDumpName' is required.`);
          }
          const folderNames = scriptController.getFolderNames(dumpDbPath);
          if (!folderNames.includes(dbDumpName)) {
            return ApiResponse.badRequest(request, response, `'dbDumpName' is not avaiable.`);
          }
          const isDumped = await scriptController.restoreDatabaseScript(dbDumpName + '/');
          return ApiResponse.ok(request, response, { isDumped });
        }

        case 'addSortToModules': {
          let cohorts: ICohortDoc[] = await CohortModel.find({});
          await Promise.all(
            cohorts.map(async (cohort: ICohortDoc) => {
              let cohortModules: IModuleDoc[] = cohort.modules;
              cohortModules = cohortModules.map((module: IModuleDoc, index) => {
                module.displayOrder = index + 1;
                return module;
              });
              await CohortModel.findOneAndUpdate({ _id: cohort._id }, { $set: { modules: cohortModules } });
              return cohort;
            })
          );
          cohorts = await CohortModel.find({});
          return ApiResponse.ok(request, response, { cohorts });
        }
        default: {
          return ApiResponse.badRequest(request, response, 'No script exists.');
        }
      }
    } catch (error) {
      console.log(error);
      return ApiResponse.internalServerError(request, response, error);
    }
  },
};

export default scriptController;
