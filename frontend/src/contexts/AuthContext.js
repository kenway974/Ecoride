import { createContext, useContext, useState } from "react";
import axiosClient from "../AxiosClient";

// Création du contexte
const AuthContext = createContext();

// Hook pour l’utiliser facilement
export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
  const [token, setToken] = useState(localStorage.getItem("token") || null);
  const [loading, setLoading] = useState(false);

  // Fonction pour login
  const login = async (email, password) => {
    setLoading(true);
    try {
      const { data } = await axiosClient.post("/login", { email, password });
      setToken(data.token);
      localStorage.setItem("token", data.token);
      setLoading(false);
      return true;
    } catch (err) {
      setLoading(false);
      throw err;
    }
  };

  // Fonction pour logout
  const logout = () => {
    setToken(null);
    localStorage.removeItem("token");
    // optionnel : appeler un endpoint backend pour supprimer le refresh token cookie
  };

  // Fonction pour refresh token
  const refreshToken = async () => {
    try {
      const { data } = await axiosClient.post("/token/refresh"); // le cookie HttpOnly est envoyé automatiquement
      setToken(data.token);
      localStorage.setItem("token", data.token);
      return data.token;
    } catch (err) {
      logout();
      throw err;
    }
  };

  // Axios interceptor pour ajouter le JWT et refresh si 401
  axiosClient.interceptors.request.use((config) => {
    if (token) {
      config.headers["Authorization"] = `Bearer ${token}`;
    }
    return config;
  });

  axiosClient.interceptors.response.use(
    (response) => response,
    async (error) => {
      const originalRequest = error.config;
      if (
        error.response &&
        error.response.status === 401 &&
        !originalRequest._retry
      ) {
        originalRequest._retry = true;
        const newToken = await refreshToken();
        originalRequest.headers["Authorization"] = `Bearer ${newToken}`;
        return axiosClient(originalRequest);
      }
      return Promise.reject(error);
    }
  );

  return (
    <AuthContext.Provider value={{ token, login, logout, loading }}>
      {children}
    </AuthContext.Provider>
  );
};
