import Joi from 'joi';

const clientGroupValidations = {
  'PUT:/api/v1/user/group': Joi.object({
    _id: Joi.string().alphanum().length(24).required(),
    name: Joi.string().required(),
    description: Joi.string().required(),
  }),
  'POST:/api/v1/user/group/member/bulk': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    // memberIds: Joi.array().items(Joi.string().alphanum().length(24)),
    members: Joi.array().items(
      Joi.object({
        memberId: Joi.string().alphanum().length(24).required(),
        canPost: Joi.boolean().required(),
        canComment: Joi.boolean().required(),
      })
    ),
  }),
  'DELETE:/api/v1/user/group/member': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    memberId: Joi.string().alphanum().length(24).required(),
  }),
  'DELETE:/api/v1/user/group/member/bulk': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    memberIds: Joi.array().items(Joi.string().alphanum().length(24)),
  }),
  'PUT:/api/v1/user/group/member': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    memberId: Joi.string().alphanum().length(24).required(),
    canPost: Joi.boolean().required(),
    canComment: Joi.boolean().required(),
  }),
};

export { clientGroupValidations };
