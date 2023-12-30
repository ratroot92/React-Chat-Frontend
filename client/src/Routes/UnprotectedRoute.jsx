import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const UnprotectedRoute = (props) => {
  const { authState } = useAuth();

  return authState.isAuthenticated ? (
    <Navigate to="/chat" />
  ) : (
    <Outlet {...props} />
  );
};

export default UnprotectedRoute;
