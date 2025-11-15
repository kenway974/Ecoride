import { createContext, useContext, useState } from "react";
import axiosClient from "../AxiosClient";
import { useNavigate } from "react-router-dom";

const AuthContext = createContext();

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
  const navigate = useNavigate();
  const [token, setToken] = useState(localStorage.getItem("token") || null);
  const [loading, setLoading] = useState(false);

  // Login minimaliste
  const login = async (email, password) => {
    setLoading(true);
    try {
      const { data } = await axiosClient.post("/login_check", { email, password });

      setToken(data.token);
      localStorage.setItem("token", data.token);

      setLoading(false);
      navigate("/user_dashboard");
      return true;
    } catch (err) {
      setLoading(false);
      throw err;
    }
  };

  // Logout
  const logout = () => {
    setToken(null);
    localStorage.removeItem("token");
    navigate("/login");
  };

  // Pour récupérer l'utilisateur à la demande
  const fetchUser = async () => {
    if (!token) return null;

    try {
      const { data } = await axiosClient.get("/user_dashboard", {
        headers: { Authorization: `Bearer ${token}` },
      });
      return data.user;
    } catch (err) {
      logout();
      throw err;
    }
  };

  return (
    <AuthContext.Provider value={{ token, loading, login, logout, fetchUser }}>
      {children}
    </AuthContext.Provider>
  );
};
