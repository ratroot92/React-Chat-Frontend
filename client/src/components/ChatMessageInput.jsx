/* eslint-disable react-hooks/exhaustive-deps */
import React, { useRef, useState } from "react";
import socketClient from "../config/socket.config";
import { useAuth } from "../context/AuthContext";
import { useChat } from "../context/ChatContext";
import "./ChatMessageInput.css"; // Import your CSS file for styling

function ChatMessageInput({}) {
  const { authState } = useAuth();
  const { chatState } = useChat();
  const [content, setContent] = useState("");
  const [isTyping, setIsTyping] = useState(false);
  const inputRef = useRef(null);
  let typingTimeout = null;

  async function sendMessage(content) {
    try {
      const recieverUserId = chatState?.selectedChat?.users.filter(
        (user) => user?._id !== authState?.user?._id
      )[0]._id;
      const data = {
        chatId: chatState.selectedChat._id,
        recieverUserId,
        content,
      };
      console.log("onSendChatMessage =>",data)
      socketClient.emit("onSendChatMessage", data);
    } catch (err) {
      console.error("ChatMessageInput [err]", err);
    }
  }

  const startTypingAnimation = () => {
    if (!content.trim()) {
      setIsTyping(true);
    }
  };

  const stopTypingAnimation = () => {
    setIsTyping(false);
  };

  React.useEffect(() => {
    function onChatMessageStartTyping(recieverId) {
      console.log("onChatMessageStartTyping",recieverId,authState?.user?._id)
      if (authState?.user?._id === recieverId) {
        startTypingAnimation();
        if (typingTimeout) {
          clearTimeout(typingTimeout);
        }
        typingTimeout = setTimeout(() => {
          stopTypingAnimation();
        }, 3000);
      }
    }

    socketClient.on("onChatMessageStartTyping", onChatMessageStartTyping);

    return () => {
      socketClient.off("onChatMessageStartTyping", onChatMessageStartTyping);
    };
  }, [content]);

  return (
    <div className={`typing-input-container ${isTyping ? "typing" : ""}`}>
      <input
        ref={inputRef}
        onKeyDown={(e) => {
          if(content){
            if (e.key === "Enter") {
              sendMessage(content);
              setContent("");
            } else {
              const recieverUserId = chatState?.selectedChat?.users.filter(
                (user) => user?._id !== authState?.user?._id
              )[0]._id;
              if (recieverUserId) {
                socketClient.emit("messageTypingStart", recieverUserId);
              }
            }
          }else{
            console.log("Write some content first.")
          }

        }}
        id="sendMessageTextBox"
        type="text"
        name="content"
        value={content || ""}
        onChange={(e) => setContent(e.target.value)}
        placeholder={isTyping ? "" : "Type a message"}
        className="form-control form-control-lg"
      />
      {isTyping && <div className="typing-indicator"></div>}
    </div>
  );
}

export default ChatMessageInput;
