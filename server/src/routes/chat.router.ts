import express from 'express';

import chatController from '../controller/chat.controller';
import { ProtectClient } from '../passport';

const chatRouter = express.Router();
// Chat Routes
chatRouter.post(`/init`, ProtectClient, chatController.createChat);
chatRouter.get(`/`, ProtectClient, chatController.fetchClientChats);
chatRouter.delete(`/:chatId`, ProtectClient, chatController.deleteClientChat);

// Message Routes
chatRouter.get(`/:chatId/messages`, ProtectClient, chatController.fetchChatMessages);
chatRouter.post(`/send-message`, ProtectClient, chatController.createChatMessage);

chatRouter.get(`/chat-users`, chatController.chatUsers);
chatRouter.post(`/access-chat`, chatController.accessChat);

chatRouter.post(`/fetch-chats`, chatController.accessChat);
chatRouter.post(`/create-group-chat`, chatController.accessChat);
chatRouter.post(`/group-exit`, chatController.accessChat);
chatRouter.post(`/add-to-group-chat`, chatController.accessChat);

export default chatRouter;
