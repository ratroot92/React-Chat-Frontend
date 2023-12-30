import "bootstrap/dist/css/bootstrap.min.css";
import React from "react";
import { Route, BrowserRouter as Router, Routes } from "react-router-dom";
import "./App.css";
import UnprotectedRoute from "./Routes/UnprotectedRoute";
import HomePage from "./pages/HomePage";
import LoginPage from "./pages/LoginPage";

import ProtectedRoute from "./Routes/ProtectedRoute";
const NotFound = () => {
  return <h1>404 Not Found</h1>;
};

const App = () => {
  return (
    <Router>
      <Routes>
        <Route element={<UnprotectedRoute />}>
          <Route path="/" element={<LoginPage />} />
        </Route>
        <Route element={<ProtectedRoute />}>
          <Route path="/chat" element={<HomePage />} />
        </Route>
        <Route path="/404" element={<NotFound />} />
      </Routes>
    </Router>
  );
};

export default App;
