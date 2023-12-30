import React, { createContext, useContext, useReducer } from "react";
import apiServer from "../config/axios.config";

const initialState = {
  isAuthenticated: false,
  user: null,
};

const AuthContext = createContext();

const authReducer = (authState, action) => {
  switch (action.type) {
    case "LOGIN":
      return {
        ...authState,
        isAuthenticated: true,
        user: action.payload.user,
      };
    case "LOGOUT":
      return {
        ...authState,
        isAuthenticated: false,
        user: null,
      };
    default:
      return authState;
  }
};

export const AuthProvider = ({ children }) => {
  const [authState, dispatch] = useReducer(authReducer, initialState);

  React.useEffect(() => {
    localStorage.setItem("previousSelectedChatId","null")

    apiServer
      .get(`/auth/is-authenticated`)
      .then((apiResponse) => {
        if (apiResponse.status >= 200 && apiResponse.status <= 299) {
          login(apiResponse.data.data.user);
        }
      })
      .catch((err) => {
        localStorage.setItem("accessToken", null);

        console.log("Err", err);
        if (err.code === "ERR_NETWORK") {
          console.log(`${err.code} Backend is not avaliable.`);
        }
      });
  }, []);

  const login = (user) => {
    dispatch({ type: "LOGIN", payload: { user } });
  };

  const logout = () => {
    dispatch({ type: "LOGOUT" });
  };

  return (
    <AuthContext.Provider value={{ authState, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
};
