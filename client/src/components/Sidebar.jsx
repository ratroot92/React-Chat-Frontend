import React from "react";
import { useChat } from "../context/ChatContext";
import SidebarItem from "./SidebarItem";

function Sidebar() {
  const { chatState, setUserChats } = useChat();

  React.useEffect(() => {
    if (!chatState?.chats.length) {
      setUserChats();
    }
  }, [chatState?.chats]);
  return (
    <div className="col-lg-4 col-md-3 border">
      <div className="row">
        <div className="col-md-12">
          <div className="row">
            <div className="col-md-12 text-center">
              <h5>Chat Message</h5>
            </div>
          </div>
        </div>
        <div className="col-md-12 ">
          <div className="row">
            {chatState?.chats?.map((chat) => (
              <SidebarItem key={chat._id} chat={chat} />
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

export default Sidebar;
