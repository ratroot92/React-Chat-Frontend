import Joi from 'joi';

const groupValidation = {
  'PUT:/api/v1/admin/group': Joi.object({
    _id: Joi.string().alphanum().length(24).required(),
    name: Joi.string()
      // .pattern(/^[a-zA-Z\s]*$/)
      .min(3)
      .max(50)
      .required(),
    description: Joi.string().min(3).max(200).required(),
    // whoCanViewPost: Joi.string().valid('OWNER', 'GROUP_MEMBERS').required(),
    // whoCanPost: Joi.string().valid('OWNER', 'GROUP_MEMBERS').required(),
    isModerated: Joi.boolean().required(),
    moderatorSettings: Joi.when('isModerated', {
      is: true,
      then: Joi.object({
        moderator: Joi.string().alphanum().length(24).required(),
        canDeletePost: Joi.boolean().required(),
        canCreatePost: Joi.boolean().required(),
        canModifyClients: Joi.boolean().required(),
      }),
      otherwise: Joi.forbidden(), // Use .forbidden() to indicate that the field should not exist
    }).required(),
  }),

  'DELETE:/api/v1/admin/group': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
  }),

  'POST:/api/v1/admin/group/member': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    memberId: Joi.string().alphanum().length(24).required(),
    canPost: Joi.boolean().required(),
    canComment: Joi.boolean().required(),
  }),
  'PUT:/api/v1/admin/group/member': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    memberId: Joi.string().alphanum().length(24).required(),
    canPost: Joi.boolean().required(),
    canComment: Joi.boolean().required(),
  }),

  'DELETE:/api/v1/admin/group/member': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    memberId: Joi.string().alphanum().length(24).required(),
  }),
  'POST:/api/v1/admin/group/member/bulk': Joi.object({
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
  'DELETE:/api/v1/admin/group/member/bulk': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    memberIds: Joi.array().items(Joi.string().alphanum().length(24)),
  }),
  'POST:/api/v1/admin/group/post/approve': Joi.object({
    postId: Joi.string().alphanum().length(24).required(),
  }),
};

export { groupValidation };
