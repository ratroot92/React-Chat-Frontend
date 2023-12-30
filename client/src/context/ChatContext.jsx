import React, { createContext, useContext, useReducer } from "react";
import apiServer from "../config/axios.config";

const initialState = {
  chats: [],
  selectedChat: null,
  chatMessages: [],
  paginationMeta: {
    resultPerPage: 0,
    totalRecords: 0,
    totalPages: 0,
    currentPage: 1,
  },
};

const ChatContext = createContext();

const chatReducer = (chatState, action) => {
  switch (action.type) {
    case "SET_USER_CHATS":
      return {
        ...chatState,
        chats: action.payload.chats,
        selectedChat: action.payload.selectedChat,
      };
    case "SET_SELECED_CHAT_MESSAGES":
      return {
        ...chatState,
        chatMessages: action.payload.chatMessages,
        paginationMeta:{
          ...chatState.paginationMeta,
          resultPerPage: action.payload.paginationMeta.resultPerPage,
          totalRecords: action.payload.paginationMeta.totalRecords,
          totalPages: action.payload.paginationMeta.totalPages,
          currentPage: action.payload.paginationMeta.currentPage,
        }
      };
    case "SET_SELECED_CHAT_OLD_MESSAGES":
      //TODO: this logic was added such that server reverse pagination is last page was fetching correct lesser results incase they were remaining
      //      lesser records for last page but it is now solved at server api
      //       this logic could be use as now as extra safety layer
      // const renderedMessagesUniqueIds=chatState.chatMessages.map(msg=>msg._id)
      // const { duplicatesMessages, uniqueMessages } = action.payload.chatMessages.reduce(
      //   (data, msg) => {
      //     if (renderedMessagesUniqueIds.includes(msg._id)) {
      //       data.duplicatesMessages.push(msg);
      //     } else {
      //       data.uniqueMessages.push(msg);
      //     }
      //     return data;
      //   },
      //   { duplicatesMessages: [], uniqueMessages: [] }
      // );
      return {
        ...chatState,
        chatMessages: [
          ...action.payload.chatMessages,
          ...chatState.chatMessages,
        ],
        paginationMeta:{
          ...chatState.paginationMeta,
          resultPerPage: action.payload.paginationMeta.resultPerPage,
          totalRecords: action.payload.paginationMeta.totalRecords,
          totalPages: action.payload.paginationMeta.totalPages,
          currentPage: action.payload.paginationMeta.currentPage,
        }
      };

    case "SET_NEW_CHAT_MESSAGE": {
      if (
        !chatState.chatMessages
          .map((msg) => msg._id)
          .includes(action.payload.newMessage._id)
      ) {
        return {
          ...chatState,
          chatMessages: [...chatState.chatMessages, action.payload.newMessage],
          selectedChat: {
            ...chatState.selectedChat,
            lastMessage: action.payload.newMessage,
          },
          chats: chatState.chats.map((chat) => {
            if (chat._id === action.payload.newMessage.chat._id) {
              return {
                ...chatState.selectedChat,
                lastMessage: action.payload.newMessage,
              };
            } else {
              return chat;
            }
          }),
        };
      } else {
        return chatState;
      }
    }

    case "SET_SELECTED_CHAT": {
      return {
        ...chatState,
        selectedChat: action.payload.selectedChat,
      };
    }
    default:
      return chatState;
  }
};

export const ChatProvider = ({ children }) => {
  const [chatState, dispatch] = useReducer(chatReducer, initialState);

  React.useEffect(() => {}, [chatState]);

  React.useEffect(() => {
    if (chatState.selectedChat) {
      const previousChatId = localStorage.getItem("previousSelectedChatId");
      if (previousChatId !== chatState.selectedChat._id) {
        localStorage.setItem(
          "previousSelectedChatId",
          chatState.selectedChat._id
        );
        setChatMessages();
      }
    }
  }, [chatState.selectedChat]);

  const setUserChats = () => {
    apiServer
      .get(`/chat`)
      .then((apiResponse) => {
        if (apiResponse.status >= 200 && apiResponse.status <= 299) {
          dispatch({
            type: "SET_USER_CHATS",
            payload: {
              chats: apiResponse.data.data.chats || [],
              selectedChat: apiResponse.data.data.chats[0] || null,
            },
          });
        }
      })
      .catch((err) => {
        console.error("Err", err);
      });
  };

  const setSelectedChat = (chatId) => {
    dispatch({
      type: "SET_SELECTED_CHAT",
      payload: {
        selectedChat: chatState.chats.filter((chat) => chat._id === chatId)[0],
      },
    });
  };

  const setNewChatMessage = (newMessage) => {
    dispatch({
      type: "SET_NEW_CHAT_MESSAGE",
      payload: { newMessage },
    });
  };

  const setChatMessages = (page) => {
    if (!page) {
      apiServer
        .get(`/chat/${chatState.selectedChat._id}/messages?page=${chatState?.paginationMeta?.currentPage}`)
        .then((apiResponse) => {
          if (apiResponse.status >= 200 && apiResponse.status <= 299) {
            dispatch({
              type: "SET_SELECED_CHAT_MESSAGES",
              payload: {
                chatMessages: apiResponse.data.data.messages || [],
                paginationMeta:apiResponse.data.data.paginationMeta
              },
            });
          }
        })
        .catch((err) => {
          console.error("Err", err);
        });
    } else {
      if(page<=chatState?.paginationMeta?.totalPages){
        apiServer
        .get(`/chat/${chatState.selectedChat._id}/messages?page=${page}`)
        .then((apiResponse) => {
          if (apiResponse.status >= 200 && apiResponse.status <= 299) {
            dispatch({
              type: "SET_SELECED_CHAT_OLD_MESSAGES",
              payload: {
                chatMessages: apiResponse.data.data.messages || [],
                paginationMeta:apiResponse.data.data.paginationMeta

              },
            });
          }
        })
        .catch((err) => {
          console.error("Err", err);
        });
      }

    }
  };
  return (
    <ChatContext.Provider
      value={{
        chatState,
        setUserChats,
        setSelectedChat,
        setNewChatMessage,
        setChatMessages,
      }}
    >
      {children}
    </ChatContext.Provider>
  );
};

export const useChat = () => {
  const context = useContext(ChatContext);
  if (!context) {
    throw new Error("useChat must be used within an ChatProvider");
  }
  return context;
};
