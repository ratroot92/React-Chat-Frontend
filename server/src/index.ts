// /* eslint-disable @typescript-eslint/no-var-requires */

// api.run();
// mongodb.connect();
// const app = api.app;
// export { app };

/* eslint-disable security/detect-non-literal-fs-filename */
/* eslint-disable @typescript-eslint/ban-ts-comment */
import express from 'express';
import http from 'http';

import { json, urlencoded } from 'body-parser';
import compression from 'compression';
import cookieParser from 'cookie-parser';
import cors from 'cors';
import { NextFunction, Request, Response } from 'express';
import jwt from 'jsonwebtoken';
import socketIO from 'socket.io';
import RedisCache from './cache/redis';
import { validateRequest } from './helpers';
import { ApiResponse } from './helpers/api';
import ErrorHandlerMiddleware from './helpers/error-handler-middleware';
import { MongoDb } from './helpers/mongodb/mongo-helper';
import { ChatModel } from './models/Chat';
import { IMessageDoc, MessageModel } from './models/Message';
import { UserModel } from './models/User';
import appRouter from './routes/index.router';
require('dotenv').config({ path: process.cwd() + `/.env.${process.env.NODE_ENV}` });
const mongodb = new MongoDb();
const maxAge = 365 * 24 * 60 * 60; // 1 year
const PORT = process.env.APP_PORT;
const HOST = process.env.APP_HOST;
declare module 'express' {
  interface Express {
    use: any;
  }
}

declare module 'socket.io' {
  interface Socket {
    user: any;
  }
}
declare module 'express-serve-static-core' {
  export interface Request {
    url: string;
    method: string;
    originalUrl: string;
    //@ts-ignore
    body: any;
    //@ts-ignore
    query: any;
  }
}

const app: express.Express = express();
const router: express.Router = express.Router();

function inCommingRequestLogger() {
  return (request: Request, response: Response, next: NextFunction) => {
    console.log(`${process.env.NODE_ENV} [${request.method}] : ${request.originalUrl}`);
    return next();
  };
}

app.use(inCommingRequestLogger());
app.use(compression());
app.use(cors({ origin: '*', methods: ['GET', 'POST', 'PATCH', 'DELETE', 'PUT', 'HEAD'], allowedHeaders: '*' }));
app.use(cookieParser());
app.use(json({ limit: '50mb' }));
app.use(urlencoded({ limit: '50mb', extended: true }));
app.use(validateRequest());
app.use('/api/v1', appRouter);

// Routes End

app.use((request: Request, response: Response, next: NextFunction) => {
  for (const key in request.query) {
    if (key) {
      request.query[key.toLowerCase()] = request.query[key];
    }
  }
  next();
});
app.use(ErrorHandlerMiddleware);
app.use((request: Request, response: Response) => {
  ApiResponse.notFound(request, response);
});

const server = http.createServer(app);

const io = new socketIO.Server(server, {
  cors: {
    origin: '*',
  },
});

io.use(async (socket, next) => {
  try {
    if (socket.handshake.headers.authorization) {
      const [authType, accessToken] = socket.handshake.headers.authorization.split(' ');
      if (accessToken && authType === 'Bearer') {
        const secret = process.env.JWT_CLIENT_SECRET || 'clientSecret';
        const payload = jwt.verify(accessToken, secret);
        if (payload) {
          //@ts-ignore
          const user = await UserModel.findOne({ _id: payload.sub._id, isAdminApproved: true });
          if (user) {
            socket.user = payload.sub;
            console.log('=> Socket Authenticated User ', user.name.toLowerCase());
            return next();
          }
        }
      }
    }
  } catch (err) {
    console.log('====================================');
    console.log('Socket Middleware Error :: ', err);
    console.log('====================================');
  }
});

io.on('connection', async function (socket: socketIO.Socket) {
  const userAlreadyConnectedToSocket = await RedisCache.get(socket.user._id);
  console.log(`=> User ${socket.user.name.toLowerCase()} is already connected to Socket`);
  if (!userAlreadyConnectedToSocket) {
    await RedisCache.set(socket.user._id, socket.id);
  }
  socket.on('messageTypingStart', async function (recieverUserId) {
    if (recieverUserId) {
      const recieverSocketId = await RedisCache.get(recieverUserId);
      if (recieverSocketId) {
        io.to(recieverSocketId).emit('onChatMessageStartTyping', recieverUserId);
      } else {
        console.log('BROADCASTED :: [messageTypingStart]');
        io.emit('onChatMessageStartTyping', recieverUserId);
      }
    }
  });

  socket.on('joinChatRoom', async ({ chat, joiner }) => {
    try {
      if (chat && joiner) {
        console.log('joinChatRoom', chat.users.map((user: any) => user?._id).join('-'));
        const chatRoomName = chat.users.map((user: any) => user?._id).join('-');
        socket.join(chatRoomName);
        io.to(chatRoomName).emit('joinChatRoom', { joiner });
      }
    } catch (err) {
      console.log('joinChatRoom', err);
    }
  });

  socket.on('onSendChatMessage', async function (message) {
    try {
      if (message) {
        const { chatId, content, recieverUserId } = message;
        if (chatId && content && recieverUserId && socket.user._id) {
          const chatExists = await ChatModel.findOne({ _id: chatId }, { isGroupChat: false });
          if (chatExists) {
            let message: IMessageDoc | null = await MessageModel.create({ sender: socket.user._id, reciever: recieverUserId, content, chat: chatExists._id, createdAt: new Date(), updatedAt: new Date() });
            if (message) {
              message = await MessageModel.findById(message._id)
                .populate('sender', '_id name email avatar')
                .populate('reciever', '_id name email avatar')
                .populate({
                  path: 'chat',
                  populate: { path: 'users', select: '_id name email avatar' },
                });
              await ChatModel.findByIdAndUpdate({ _id: chatExists._id }, { lastMessage: message, updatedAt: new Date() });
              const chatRoomName = chatExists.users.map((user: any) => user?._id).join('-');
              io.to(chatRoomName).emit('recieveChatMessage', message);
            }
          }
        }
      }
    } catch (err) {
      console.log(err);
    }
  });

  socket.on('disconnect', async () => {
    console.log('=> Socket Disconnected With User ', socket.user.name.toLowerCase());
    await RedisCache.del(socket.user._id);
  });
});

server.listen(PORT, () => {
  console.log(`Server listening at ${HOST}:${PORT} in '${process.env.NODE_ENV}' Mode.`);
});
server.on('error', onServerError);

function onServerError(error: any) {
  const PORT = error.PORT;
  if (error.syscall !== 'listen') {
    throw error;
  }

  const bind = typeof PORT === 'string' ? `Pipe ${PORT}` : `Port ${PORT}`;

  switch (error.code) {
    case 'EACCES':
      console.error(`${bind} requires elevated privileges`);
      process.exit(1);
    case 'EADDRINUSE':
      console.error(`${bind} is already in use`);
      process.exit(1);
    default:
      throw error;
  }
}
