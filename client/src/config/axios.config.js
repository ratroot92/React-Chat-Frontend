import axios from "axios";

const apiServer = axios.create({
  baseURL: "http://ec2-16-171-136-34.eu-north-1.compute.amazonaws.com:8080/api/v1",
  timeout: 5000,
  headers: { "Content-Type": "application/json" },
});

apiServer.interceptors.request.use(
  (config) => {
    const accessToken = localStorage.getItem("accessToken");
    if (accessToken != null) {
      config.headers["Authorization"] = "Bearer " + accessToken;
    }
    return config;
  },
  (error) => {
    console.log("=================================");
    console.log(error);
    console.log("=================================");
    return Promise.reject(error);
  }
);

axios.interceptors.response.use(
  (response) => {
    console.log("=================================");
    console.log("response", response);
    console.log("=================================");
    return response;
  },
  (error) => {
    console.log("=================================");
    console.log(error);
    console.log("=================================");
    return Promise.reject(error);
  }
);

export default apiServer;
