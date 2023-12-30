import express from 'express';
import adminCohortController from '../../controller/admin/cohort.controller';
import { ensureDir } from '../../helpers';
import { ProtectAdmin } from '../../passport';

const adminCohortRouter = express.Router();
// const routerPrefix = '/admin/cohort';
adminCohortRouter.post(`/`, ProtectAdmin, adminCohortController.createCohort);
adminCohortRouter.put(`/`, ProtectAdmin, adminCohortController.editCohort);
adminCohortRouter.get(`/`, ProtectAdmin, adminCohortController.getAllCohorts);
adminCohortRouter.delete(`/`, ProtectAdmin, adminCohortController.deleteCohortById);

// Modules Routes
adminCohortRouter.post(`/module`, ProtectAdmin, adminCohortController.addModule);
adminCohortRouter.delete(`/module`, ProtectAdmin, adminCohortController.deleteModule);
adminCohortRouter.put(`/module`, ProtectAdmin, adminCohortController.editModule);
adminCohortRouter.put(`/module/order`, ProtectAdmin, adminCohortController.reOrderModules);

// Day Routes Routes
adminCohortRouter.post(`/module/day`, ProtectAdmin, adminCohortController.addDay);
adminCohortRouter.delete(`/module/day`, ProtectAdmin, adminCohortController.deleteDay);
adminCohortRouter.put(`/module/day`, ProtectAdmin, adminCohortController.editDay);

// Day Pdf Routes
adminCohortRouter.post(`/module/day/pdf`, ProtectAdmin, ensureDir(`${process.cwd()}/public/cohort/pdf`), adminCohortController.uploadPdf, adminCohortController.uploadPdfDocument);
adminCohortRouter.put(`/module/day/pdf`, ProtectAdmin, adminCohortController.uploadPdf, adminCohortController.editPdfDocument);
adminCohortRouter.delete(`/module/day/pdf`, ProtectAdmin, adminCohortController.deletePdfDocument);

// Day Docx Routes
adminCohortRouter.post(`/module/day/doc`, ProtectAdmin, ensureDir(`${process.cwd()}/public/cohort/doc`), adminCohortController.uploadDocx, adminCohortController.uploadDocxDocument);
adminCohortRouter.put(`/module/day/doc`, ProtectAdmin, adminCohortController.uploadDocx, adminCohortController.editDocxDocument);
adminCohortRouter.delete(`/module/day/doc`, ProtectAdmin, adminCohortController.deleteDocxDocument);
// Day Ppt Routes
adminCohortRouter.post(`/module/day/ppt`, ProtectAdmin, ensureDir(`${process.cwd()}/public/cohort/ppt`), adminCohortController.uploadPpt, adminCohortController.uploadPptDocument);
adminCohortRouter.put(`/module/day/ppt`, ProtectAdmin, adminCohortController.uploadPpt, adminCohortController.editPptDocument);
adminCohortRouter.delete(`/module/day/ppt`, ProtectAdmin, adminCohortController.deletePptDocument);

// Day key Routes
adminCohortRouter.post(`/module/day/key`, ProtectAdmin, ensureDir(`${process.cwd()}/public/cohort/key`), adminCohortController.uploadKey, adminCohortController.uploadKeyDocument);
adminCohortRouter.put(`/module/day/key`, ProtectAdmin, adminCohortController.uploadKey, adminCohortController.editKeyDocument);
adminCohortRouter.delete(`/module/day/key`, ProtectAdmin, adminCohortController.deleteKeyDocument);
// Day Vimeo Routes
adminCohortRouter.post(`/module/day/vimeo`, ProtectAdmin, adminCohortController.uploadVimeoVideo);
adminCohortRouter.put(`/module/day/vimeo`, ProtectAdmin, adminCohortController.editVimeoVideo);
adminCohortRouter.delete(`/module/day/vimeo`, ProtectAdmin, adminCohortController.deleteVimeoVideo);

// Assign & Unasign modules to user
adminCohortRouter.post(`/module/assign`, ProtectAdmin, adminCohortController.assignModuleToUser);
adminCohortRouter.post(`/module/assign-multiple`, ProtectAdmin, adminCohortController.assignMultipleModuleToUser);
adminCohortRouter.post(`/module/unassign`, ProtectAdmin, adminCohortController.unAssignModuleToUser);
export default adminCohortRouter;
