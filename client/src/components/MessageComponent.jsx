import moment from "moment";
import React from "react";
import { useAuth } from "../context/AuthContext";

const TimeAgo = ({ timestamp }) => {
  const [currentTimeAgo, setCurrentTimeAgo] = React.useState(
    moment(timestamp).fromNow()
  );

  React.useEffect(() => {
    const intervalId = setInterval(() => {
      setCurrentTimeAgo(moment(timestamp).fromNow());
    }, 30000);

    return () => clearInterval(intervalId);
  }, [timestamp]);

  return <span style={{ fontSize: "9px" }}> {currentTimeAgo}</span>;
};

function MessengerName({ chatMessage }) {
  return (
    <span style={{ fontSize: "9px", margin: "0px 5px 0px 5px" }}>
      ~ {chatMessage?.sender?.name}
    </span>
  );
}

function MessageHeader({ chatMessage, authState }) {
  return (
    <div
      className={`d-flex flex-col justify-content-${
        authState?.user?._id === chatMessage?.sender?._id ? "start" : "end"
      } align-items-center`}
    >
      {authState?.user?._id === chatMessage?.sender?._id ? (
        <>
          {" "}
          <MessengerName chatMessage={chatMessage} />
          <TimeAgo timestamp={chatMessage?.createdAt} />
        </>
      ) : (
        <>
          {" "}
          <TimeAgo timestamp={chatMessage?.createdAt} />
          <MessengerName chatMessage={chatMessage} />
        </>
      )}
    </div>
  );
}

function MessageContent({ chatMessage, authState }) {
  return (
    <div
      className={`d-flex flex-col align-items-end justify-content-${
        authState?.user?._id === chatMessage?.sender?._id ? "start" : "end"
      }`}
    >
      <span
        className={`text-white mt-2 bg-${
          authState?.user?._id === chatMessage?.sender?._id
            ? "success"
            : "primary"
        } `}
        style={{
          fontSize: "12px",
          borderRadius: "10px",
          padding: "3px 10px 3px 10px",
        }}
      >
        {chatMessage?.content}
      </span>
    </div>
  );
}

function UserAvatar() {
  return (
    <div className="col-md-1 d-flex justify-content-center align-items-center">
      <img
        src={avatarSource}
        height={45}
        width={45}
        style={{ borderRadius: "50%" }}
        alt=""
      />
    </div>
  );
}
const avatarSource =
  "data:image/jpeg;base64,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";
function MessageComponent({ chatMessage ,index}) {
  const { authState } = useAuth();

  return (
    <>
      {authState?.user?._id === chatMessage?.sender?._id ? (
        <li key={chatMessage._id} className="mt-3 mb-3">
          <div className="row">
            <UserAvatar />
            <div
              className="col-md-11  pt-1 pb-1"
              style={{ borderRadius: "1rem" }}
            >
              <MessageHeader authState={authState} chatMessage={chatMessage} />
              <MessageContent authState={authState} chatMessage={chatMessage} />
            </div>
          </div>
        </li>
      ) : (
        <li key={chatMessage._id} className="mt-3 mb-3">
          <div className="row">
            <div
              className="col-md-11  pt-1 pb-1"
              style={{ borderRadius: "1rem" }}
            >
              <MessageHeader authState={authState} chatMessage={chatMessage} />
              <MessageContent authState={authState} chatMessage={chatMessage} />
            </div>
            <UserAvatar />
          </div>
        </li>
      )}
    </>
  );
}

export default MessageComponent;
