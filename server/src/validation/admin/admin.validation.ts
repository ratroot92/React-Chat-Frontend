import Joi from 'joi';

const adminVCalidations = {
  'PUT:/api/v1/admin': Joi.object({
    id: Joi.string().alphanum().length(24).required(),
    name: Joi.string().optional(),
    phoneNo: Joi.string().optional(),
    notes: Joi.string().optional(),
    country: Joi.string().optional(),
    state: Joi.string().optional(),
  }),
};

export { adminVCalidations };
