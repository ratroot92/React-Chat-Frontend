import React from "react";
import Sidebar from "../components/Sidebar";

import MainTab from "../components/MainTab";
import socketClient from "../config/socket.config";
import { useChat } from "../context/ChatContext";
function HomePage() {
  const [isConnected, setIsConnected] = React.useState(socketClient.connected);
  const { chatState, setNewChatMessage } = useChat();

  React.useEffect(() => {
    function onConnect() {
      setIsConnected(true);
    }

    function onDisconnect() {
      setIsConnected(false);
    }

    function recieveChatMessage(newMessage,) {
      console.log("recieveChatMessage",newMessage?.content)
      if (chatState?.selectedChat?._id) {
        if (chatState?.selectedChat?._id === newMessage?.chat?._id) {
          if (
            chatState?.chatMessages?.filter((msg) => msg._id === newMessage._id)
              .length === 0
          ) {
            setNewChatMessage(newMessage);
          }
        }
      }
    }

    socketClient.on("connect", onConnect);
    socketClient.on("disconnect", onDisconnect);
    socketClient.on("recieveChatMessage", recieveChatMessage);

    return () => {
      socketClient.off("connect", onConnect);
      socketClient.off("disconnect", onDisconnect);
      socketClient.off("recieveChatMessage", recieveChatMessage);
    };
  }, [chatState]);

  return (
    <div className="container mt-5">
      {/* {chatState.loading === true ? ( */}
      <div className="row">
        <Sidebar chatState={chatState} />
        <MainTab />
      </div>
      {/* ) : (
        <Audio
          height="80"
          width="80"
          radius="9"
          color="green"
          ariaLabel="three-dots-loading"
          wrapperStyle
          wrapperClass
        />
      )} */}
    </div>
  );
}

export default HomePage;
