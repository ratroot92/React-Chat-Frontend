import Joi from 'joi';

const sessionValidation = {
  'POST:/api/v1/admin/session': Joi.object({
    name: Joi.string().required(),
  }),
  'DELETE:/api/v1/admin/session': Joi.object({
    id: Joi.string().alphanum().length(24).required(),
  }),
  'PATCH:/api/v1/admin/session': Joi.object({
    id: Joi.string().alphanum().length(24).required(),
    name: Joi.string().required(),
  }),
};

export { sessionValidation };
