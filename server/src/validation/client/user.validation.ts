import Joi from 'joi';

const userValidation = {
  'POST:/api/v1/user': Joi.object({
    name: Joi.string().required(),
    phoneNo: Joi.string().required(),
    email: Joi.string().email().required(),
    password: Joi.string().min(6).required(),
    confirmPassword: Joi.string().valid(Joi.ref('password')).required().strict(),
    termsAccepted: Joi.boolean().valid(true).required(),
  }),
  'POST:/api/v1/user/password': Joi.object({
    email: Joi.string().email().required(),
  }),
  'POST:/api/v1/user/verify-otp': Joi.object({
    email: Joi.string().email().required(),
    otp: Joi.string().required(),
  }),
  'POST:/api/v1/user/password/reset': Joi.object({
    email: Joi.string().email().required(),
    password: Joi.string().min(6).required(),
    confirmPassword: Joi.string().valid(Joi.ref('password')).required().strict(),
  }),
  'PATCH:/api/v1/user': Joi.object({
    id: Joi.string().alphanum().length(24).required(),
    name: Joi.string().optional(),
    phoneNo: Joi.string().optional(),
    notes: Joi.string().optional(),
    country: Joi.string().optional(),
    state: Joi.string().optional(),
  }),
};

export { userValidation };
