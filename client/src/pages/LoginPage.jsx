// LoginPage.js

import "bootstrap/dist/css/bootstrap.min.css";
import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import apiServer from "../config/axios.config";
import { useAuth } from "../context/AuthContext";

const LoginPage = () => {
  const navigate = useNavigate();
  const { state, login, logout } = useAuth();

  /**
   * "email":"iamosmanraza@gmail.com",
   * "password":"Usman@12345"
   * "email":"shahmir.quidwai@gmail.com",
   * "password":"lms@12345"
   *
   */
  const [email, setEmail] = useState("iamosmanraza@gmail.com");
  const [password, setPassword] = useState("Usman@12345");

  async function loginUser() {
    const apiResponse = await apiServer.post(`/auth/login`, {email,password});
    if (apiResponse.status >= 200 && apiResponse.status <= 299) {
      console.log("Login Api =>", apiResponse.data.data);
      return apiResponse.data.data;
    }
  }

  const handleLogin = () => {
    loginUser()
      .then(({ user, accessToken }) => {
        console.log("accessToken", accessToken);
        localStorage.setItem("accessToken", accessToken);
        login(user);

        setTimeout(() => {
          return navigate(`/chat`);
        }, 2000);
      })
      .catch((err) => {
        localStorage.setItem("accessToken", null);
        console.log("Login Err", err);
      });
  };
  return (
    <div className="container mt-5">
      <div className="row justify-content-center">
        <div className="col-md-6">
          <div className="card">
            <div className="card-header">
              <h4 className="text-center">Login</h4>
            </div>
            <div className="card-body">
              <form>
                <div className="mb-3">
                  <label htmlFor="email" className="form-label">
                    Username
                  </label>
                  <input
                    type="text"
                    className="form-control"
                    id="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                  />
                </div>
                <div className="mb-3">
                  <label htmlFor="password" className="form-label">
                    Password
                  </label>
                  <input
                    type="password"
                    className="form-control"
                    id="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                  />
                </div>
                <div className="text-center">
                  <button
                    type="button"
                    className="btn btn-primary"
                    onClick={handleLogin}
                  >
                    Login
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
