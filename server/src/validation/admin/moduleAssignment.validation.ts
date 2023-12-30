import Joi from 'joi';

const moduleAssignmentValidation = {
  'POST:/api/v1/admin/assignment': Joi.object({
    name: Joi.string().required(),
  }),
  // 'DELETE:/api/v1/admin/assignment': Joi.object({
  //   id: Joi.string().required(),
  // }),
  'PATCH:/api/v1/admin/assignment': Joi.object({
    id: Joi.string().alphanum().length(24).required(),
    name: Joi.string().required(),
  }),
};

export { moduleAssignmentValidation };
