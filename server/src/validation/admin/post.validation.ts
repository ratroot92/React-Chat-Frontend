import Joi from 'joi';

const adminPostValidation = {
  'DELETE:/api/v1/admin/post': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    postId: Joi.string().alphanum().length(24).required(),
  }),
  'POST:/api/v1/admin/post': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    mediaId: Joi.string().alphanum().length(24).optional(),
    content: Joi.when('mediaId', {
      is: Joi.exist(),
      then: Joi.string().optional(),
      otherwise: Joi.string().required(),
    }),
    title: Joi.string().required(),
  }),
  'POST:/api/v1/admin/post/comment': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    postId: Joi.string().alphanum().length(24).required(),
    content: Joi.string().required(),
  }),

  'POST:/api/v1/admin/post/like': Joi.object({
    postId: Joi.string().alphanum().length(24).required(),
  }),
  'POST:/api/v1/admin/post/unlike': Joi.object({
    postId: Joi.string().alphanum().length(24).required(),
  }),
  'DELETE:/api/v1/admin/post/comment': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    postId: Joi.string().alphanum().length(24).required(),
    commentId: Joi.string().alphanum().length(24).required(),
  }),
};

export { adminPostValidation };
