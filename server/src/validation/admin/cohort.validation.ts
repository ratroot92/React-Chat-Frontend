import Joi from 'joi';

const cohortValidation = {
  'POST:/api/v1/admin/cohort': Joi.object({
    name: Joi.string().required(),
    year: Joi.string().required(),
    session: Joi.string().required(),
  }),

  'PUT:/api/v1/admin/cohort': Joi.object({
    id: Joi.string().required(),
    name: Joi.string().optional(),
    year: Joi.string().optional(),
    session: Joi.string().optional(),
  }),

  // MODULE ROUTES

  'POST:/api/v1/admin/cohort/module': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    name: Joi.string().required(),
  }),
  'DELETE:/api/v1/admin/cohort/module': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
  }),

  'PUT:/api/v1/admin/cohort/module': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    name: Joi.string().required(),
  }),

  'PUT:/api/v1/admin/cohort/module/order': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    modulesOrder: Joi.array()
      .items(
        Joi.object({
          id: Joi.string().alphanum().length(24).required(),
          displayOrder: Joi.number().integer().required(),
        })
      )
      .required(),
  }),
  // DAY ROUTES

  'POST:/api/v1/admin/cohort/module/day': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    title: Joi.string().required(),
  }),
  'DELETE:/api/v1/admin/cohort/module/day': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    dayId: Joi.string().alphanum().length(24).required(),
  }),
  'PUT:/api/v1/admin/cohort/module/day': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    dayId: Joi.string().alphanum().length(24).required(),
    title: Joi.string().required(),
  }),

  // PDF ROUTES

  'DELETE:/api/v1/admin/cohort/module/day/pdf': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    dayId: Joi.string().alphanum().length(24).required(),
    pdfId: Joi.string().required(),
  }),
  'DELETE:/api/v1/admin/cohort/module/day/doc': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    dayId: Joi.string().alphanum().length(24).required(),
    docId: Joi.string().required(),
  }),

  'DELETE:/api/v1/admin/cohort/module/day/ppt': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    dayId: Joi.string().alphanum().length(24).required(),
    pptId: Joi.string().required(),
  }),

  'POST:/api/v1/admin/cohort/module/assign': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    userId: Joi.string().alphanum().length(24).required(),
  }),

  'POST:/api/v1/admin/cohort/module/assign-multiple': Joi.object({
    assignableModules: Joi.array()
      .items(
        Joi.object({
          cohortId: Joi.string().alphanum().length(24).required(),
          moduleId: Joi.string().alphanum().length(24).required(),
        })
      )
      .min(1)
      .required(),
    userId: Joi.string().alphanum().length(24).required(),
  }),

  'POST:/api/v1/admin/cohort/module/unassign': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    userId: Joi.string().alphanum().length(24).required(),
  }),

  'POST:/api/v1/admin/cohort/module/day/vimeo': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    dayId: Joi.string().alphanum().length(24).required(),
    vimeoUrl: Joi.string().required(),
    vimeoTitle: Joi.string().required(),
    isDownloadable: Joi.boolean().required(),
  }),

  'PUT:/api/v1/admin/cohort/module/day/vimeo': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    dayId: Joi.string().alphanum().length(24).required(),
    vimeoId: Joi.string().required(),
    vimeoUrl: Joi.string().required(),
    vimeoTitle: Joi.string().required(),
    isDownloadable: Joi.boolean().required(),
  }),

  'DELETE:/api/v1/admin/cohort/module/day/vimeo': Joi.object({
    cohortId: Joi.string().alphanum().length(24).required(),
    moduleId: Joi.string().alphanum().length(24).required(),
    dayId: Joi.string().alphanum().length(24).required(),
    vimeoId: Joi.string().required(),
  }),
};

export { cohortValidation };
