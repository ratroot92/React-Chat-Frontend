import { io } from "socket.io-client";

const URL =
  process.env.NODE_ENV === "production" ? undefined : "http://localhost:8080";

const accessToken = localStorage.getItem("accessToken");

// const socketClient = io("http://localhost:8080", {
const socketClient = io("http://ec2-16-171-136-34.eu-north-1.compute.amazonaws.com:8080", {
  autoConnect: true,
  extraHeaders: {
    Authorization: `Bearer ${accessToken}`,
  },
});

export default socketClient;
