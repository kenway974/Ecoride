import { Routes, Route, Navigate } from "react-router-dom";
import Home from "../pages/Home";
import Trips from "../pages/Trips";
import Login from "../pages/Login";
import Register from "../pages/Register";

// Simple guard pour les routes privées
function PrivateRoute({ children }) {
  const token = localStorage.getItem("token");
  return token ? children : <Navigate to="/login" replace />;
}

function AppRouter() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/trips" element={
        <PrivateRoute>
          <Trips />
        </PrivateRoute>
      } />
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />
      {/* fallback si page inconnue */}
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}

export default AppRouter;
