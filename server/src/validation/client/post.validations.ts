import Joi from 'joi';

const postValidation = {
  'POST:/api/v1/user/post': Joi.object({
    groupId: Joi.string().alphanum().length(24).required(),
    mediaId: Joi.string().alphanum().length(24).optional(),
    content: Joi.optional(),
    title: Joi.string().required(),
  }),

  'DELETE:/api/v1/user/post': Joi.object({
    postId: Joi.string().alphanum().length(24).required(),
    groupId: Joi.string().alphanum().length(24).required(),
  }),

  'POST:/api/v1/user/post/like': Joi.object({
    postId: Joi.string().alphanum().length(24).required(),
  }),
  'POST:/api/v1/user/post/unlike': Joi.object({
    postId: Joi.string().alphanum().length(24).required(),
  }),

  'POST:/api/v1/user/post/comment': Joi.object({
    postId: Joi.string().alphanum().length(24).required(),
    content: Joi.string().required(),
  }),

  'DELETE:/api/v1/user/post/comment': Joi.object({
    postId: Joi.string().alphanum().length(24).required(),
    commentId: Joi.string().alphanum().length(24).required(),
  }),

  'POST:/api/v1/user/post/comment/like': Joi.object({
    commentId: Joi.string().alphanum().length(24).required(),
  }),
};

export { postValidation };
