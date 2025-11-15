import { Routes, Route, Navigate } from "react-router-dom";
import Home from "../pages/Home";
import Trips from "../pages/Trips";
import Login from "../pages/Login";
import Register from "../pages/Register";
import Dashboard from "../pages/Dashboard";
import { useAuth } from "../contexts/AuthContext";



// Simple guard pour les routes privées
function PrivateRoute({ children }) {
  const token = localStorage.getItem("token");
  return token ? children : <Navigate to="/login" replace />;
}

function PublicRoute({ children }) {
  const { token } = useAuth();
  return token ? <Navigate to="/user_dashboard" replace /> : children;
}

function AppRouter() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/trips" element={<Trips />} />
      <Route path="/login" element={
        <PublicRoute>
          <Login />
        </PublicRoute>
      } />
      <Route path="/register" element={
        <PublicRoute>
        <Register />
        </PublicRoute>
      } />
      <Route path="/user_dashboard" element={
        <PrivateRoute>
          <Dashboard />
        </PrivateRoute>
      } />
      {/* fallback si page inconnue */}
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}

export default AppRouter;
