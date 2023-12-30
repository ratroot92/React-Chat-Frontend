import React from "react";
import { useAuth } from "../context/AuthContext";
import { useChat } from "../context/ChatContext";
import ChatMessageInput from "./ChatMessageInput";
import MessageComponent from "./MessageComponent";
function MainTab() {
  const { chatState ,setChatMessages} = useChat();
  const scrollRef = React.useRef(null);
  const {authState}=useAuth();
  React.useEffect(() => {
    if (scrollRef.current && chatState?.paginationMeta?.currentPage===1) {
      scrollRef.current.scrollTop = scrollRef.current.scrollHeight;
    }
    else{
        scrollRef.current.scrollTop = scrollRef.current.scrollHeight-50;
    }
  }, [chatState?.chatMessages,chatState?.paginationMeta?.currentPage]);

  function MainTabHeader() {
    return (
      <div className="row">
        <div className="col-md-12">
          <p
            className="m-0 p-0 "
            style={{ fontWeight: "bold", fontSize: "14px" }}
          >
            {chatState?.selectedChat?.users?.filter(u=>u._id!==authState?.user?._id)[0]?.name} and you
          </p>
          <p className="m-0 p-0 " style={{ fontSize: "14px" }}>
            {chatState?.selectedChat?.users?.length} people in this conversation
          </p>
        </div>
      </div>
    );
  }
  return (
    <div className="col-lg-8 col-md-9 border">
      <div className="row">
        <div className="col-md-12  border">
          <MainTabHeader />
        </div>
      </div>
      <div className="row">
        <div
        onScroll={(e)=>{
          if(scrollRef.current.scrollTop===0){
            setTimeout(()=>{
              setChatMessages(chatState?.paginationMeta?.currentPage+1)
            },1000)
          }
        }}
          className="col-md-12 p-5"
          ref={scrollRef}
          style={{
            height: "500px",
            overflowY: "scroll",
          }}
        >
          <ul className="list-unstyled">
            {chatState?.chatMessages?.map((chatMessage,index) => (
              <MessageComponent
                key={chatMessage._id}
                chatMessage={chatMessage}
                index={index}
              />
            ))}
          </ul>
        </div>
        <div className="col-md-12 mt-2 mb-2">
          <div className="row">
            <div className="col-md-12">
              <ChatMessageInput />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default MainTab;
