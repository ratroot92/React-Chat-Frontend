import Joi from 'joi';

const authValidation = {
  'POST:/api/v1/auth/register': Joi.object({
    name: Joi.string().required(),
    phoneNo: Joi.string().required(),
    email: Joi.string().email().required(),
    password: Joi.string().min(6).required(),
    confirmPassword: Joi.string().valid(Joi.ref('password')).required().strict(),
    termsAccepted: Joi.boolean().valid(true).required(),
  }),
  'POST:/api/v1/auth/login': Joi.object({
    email: Joi.string().email().required(),
    password: Joi.string().min(6).required(),
  }),
};

export { authValidation };
