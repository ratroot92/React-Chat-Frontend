import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const ProtectedRoute = (props) => {
  const { authState } = useAuth();
  return authState.isAuthenticated ? (
    <Outlet {...props} />
  ) : (
    <Navigate to="/" />
  );
};

export default ProtectedRoute;
