import Joi from 'joi';

const clientValidation = {
  'PATCH:/api/v1/admin/client': Joi.object({
    id: Joi.string().required(),
    canDownloadGlobal: Joi.boolean().optional(),
    canDownloadPpts: Joi.boolean().optional(),
    canDownloadDocs: Joi.boolean().optional(),
    canDownloadPdfs: Joi.boolean().optional(),
    canDownloadVimeosVideos: Joi.boolean().optional(),
    canWatchVimeosVideos: Joi.boolean().optional(),
  }),
};

export { clientValidation };
