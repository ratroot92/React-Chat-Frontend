/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Request, Response } from 'express';
import { ApiResponse } from '../helpers';
import ApiFeature from '../helpers/api-feature';
import { ChatModel, IChatDoc } from '../models/Chat';
import { IMessageDoc, MessageModel } from '../models/Message';
import { IUserDoc, UserModel } from '../models/User';

const chatControllerContants = {
  USER_POPULATE_SELECT: '_id name email avatar',
};

const chatController = {
  createChat: async function (request: Request, response: Response) {
    try {
      const { userId } = request.body;
      //@ts-ignore
      const clientId = request.user._id;
      //@ts-ignore
      const clientName = request.user.name;

      const userExist: IUserDoc = await UserModel.findOne({ _id: userId }, { isAdminApproved: true }).select(chatControllerContants.USER_POPULATE_SELECT);
      if (!userExist) {
        return ApiResponse.badRequest(request, response, `User not found.`);
      }
      if (userId === clientId) {
        return ApiResponse.badRequest(request, response, `Cannot initiate chat with yourself.`);
      }

      let chat: IChatDoc | null = await ChatModel.findOne({
        isGroupChat: false,
        $and: [{ users: { $elemMatch: { $eq: clientId } } }, { users: { $elemMatch: { $eq: userId } } }],
      })
        .populate('users', chatControllerContants.USER_POPULATE_SELECT)
        .populate({
          path: 'lastMessage',
          select: '-chat',
          populate: [
            { path: 'sender', select: chatControllerContants.USER_POPULATE_SELECT },
            { path: 'reciever', select: chatControllerContants.USER_POPULATE_SELECT },
          ],
        });
      if (chat) {
        return ApiResponse.ok(request, response, { chat });
      }
      const isGroupChat = false;
      chat = await ChatModel.create({
        chatName: `${clientName} - ${userExist.name}`,
        users: [clientId, userExist._id],
        isGroupChat,
        createdAt: new Date(),
        updatedAt: new Date(),
      });
      chat = await ChatModel.findOne({ _id: chat._id }).populate([{ path: 'users', select: chatControllerContants.USER_POPULATE_SELECT }]);

      return ApiResponse.ok(request, response, { chat });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  fetchClientChats: async function (request: Request, response: Response) {
    try {
      //@ts-ignore
      const clientId = request.user._id;
      const chats: IChatDoc[] = await ChatModel.find({
        isGroupChat: false,
        $and: [{ users: { $elemMatch: { $eq: clientId } } }],
      })
        .populate('users', chatControllerContants.USER_POPULATE_SELECT)
        .populate({
          path: 'lastMessage',
          select: '-chat',
          populate: [
            { path: 'sender', select: chatControllerContants.USER_POPULATE_SELECT },
            { path: 'reciever', select: chatControllerContants.USER_POPULATE_SELECT },
          ],
        })
        .populate('groupAdmin', chatControllerContants.USER_POPULATE_SELECT)
        .sort({ updatedAt: -1 });

      return ApiResponse.ok(request, response, { chats });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  deleteClientChat: async function (request: Request, response: Response) {
    try {
      //@ts-ignore
      const { chatId } = request.params;
      if (!chatId) {
        return ApiResponse.badRequest(request, response, `ChatId is required.`);
      }
      const chat: IChatDoc | null = await ChatModel.findOne({ _id: chatId });
      if (!chat) {
        return ApiResponse.badRequest(request, response, `Chat not found.`);
      }
      await ChatModel.findByIdAndDelete({ _id: chat._id });
      await MessageModel.deleteMany({ chat: chat._id });
      return ApiResponse.ok(request, response, {});
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  // Message
  fetchChatMessages: async function (request: Request, response: Response) {
    try {
      const { chatId } = request.params;
      //@ts-ignore
      const page: number = parseInt(request.query.page);
      if (!page) {
        return ApiResponse.badRequest(request, response, `page is required.`);
      }
      if (!chatId) {
        return ApiResponse.badRequest(request, response, `ChatId is required.`);
      }
      const chatExists: IChatDoc | null = await ChatModel.findOne({ _id: chatId }, { isGroupChat: false });
      if (!chatExists) {
        return ApiResponse.badRequest(request, response, `Chat not found.`);
      }
      const resultPerPage = 50;
      const totalRecords: number = await MessageModel.find({ chat: chatId }).count();
      const apiFeature = new ApiFeature(
        MessageModel.find({ chat: chatId })
          .populate('sender', chatControllerContants.USER_POPULATE_SELECT)
          .populate('reciever', chatControllerContants.USER_POPULATE_SELECT)
          .populate({
            path: 'chat',
            populate: { path: 'users', select: chatControllerContants.USER_POPULATE_SELECT },
          }),
        request.query
      ).reversePagination(resultPerPage, totalRecords);
      const totalPages = Math.ceil(totalRecords / resultPerPage);
      const messages: IMessageDoc[] = await apiFeature.query;
      return ApiResponse.ok(request, response, { messages, paginationMeta: { resultPerPage, totalRecords, totalPages, currentPage: page } });
    } catch (err) {
      console.log(err);
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  createChatMessage: async function (request: Request, response: Response) {
    try {
      const { content, chatId } = request.body;
      //@ts-ignore
      const clientId = request.user._id;
      if (!chatId) {
        return ApiResponse.badRequest(request, response, `'chatId' is required.`);
      }
      if (!content) {
        return ApiResponse.badRequest(request, response, `'content' is required.`);
      }

      const chatExists = await ChatModel.findOne({ _id: chatId }, { isGroupChat: false });
      if (!chatExists) {
        return ApiResponse.badRequest(request, response, `Chat not found.`);
      }
      let message: IMessageDoc | null = await MessageModel.create({ sender: clientId, reciever: chatExists.users[1], content, chat: chatExists._id, createdAt: new Date(), updatedAt: new Date() });
      message = await MessageModel.findById(message._id)
        .populate('sender', chatControllerContants.USER_POPULATE_SELECT)
        .populate('reciever', chatControllerContants.USER_POPULATE_SELECT)
        .populate({
          path: 'chat',
          populate: { path: 'users', select: chatControllerContants.USER_POPULATE_SELECT },
        });
      //@ts-ignore
      await ChatModel.findByIdAndUpdate({ _id: chatExists._id }, { lastMessage: message, updatedAt: new Date() });
      return ApiResponse.ok(request, response, { message });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },

  chatUsers: async function (request: Request, response: Response) {
    try {
      const users = await UserModel.find({ role: 'user', isAdminApproved: true }).select('name email avatar');
      return ApiResponse.ok(request, response, { users });
    } catch (err) {
      return ApiResponse.internalServerError(request, response, err);
    }
  },
  accessChat: async function (request: Request, response: Response) {},
  fetchChat: async function (request: Request, response: Response) {},
  createGroupChat: async function (request: Request, response: Response) {},
  fetchGroups: async function (request: Request, response: Response) {},
  groupExit: async function (request: Request, response: Response) {},
  addSelfToGroup: async function (request: Request, response: Response) {},
};

export default chatController;
