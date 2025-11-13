import { createContext, useContext, useState, useEffect } from "react";
import axiosClient from "../AxiosClient";
import { useNavigate } from "react-router-dom"; // Pour les redirections

const AuthContext = createContext();

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
  const navigate = useNavigate(); // Hook de React Router pour redirection
  const [token, setToken] = useState(localStorage.getItem("token") || null);
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(false);

  /**
   * Login
   */
  const login = async (email, password) => {
    setLoading(true);
    try {
      const { data } = await axiosClient.post("/login", { email, password });
      setToken(data.token);
      localStorage.setItem("token", data.token);
      await fetchUser(data.token); // récupère les infos de l'utilisateur
      setLoading(false);
      return true;
    } catch (err) {
      setLoading(false);
      throw err;
    }
  };

  /**
   * Logout
   */
  const logout = () => {
    setToken(null);
    setUser(null);
    localStorage.removeItem("token");
    navigate("/login"); // redirection vers login
  };

  /**
   * Refresh token
   */
  const refreshToken = async () => {
    try {
      const { data } = await axiosClient.post("/token/refresh");
      setToken(data.token);
      localStorage.setItem("token", data.token);
      return data.token;
    } catch (err) {
      logout(); // si refresh échoue, on déconnecte
      throw err;
    }
  };

  /**
   * Récupère les infos de l'utilisateur connecté
   */
  const fetchUser = async (jwtToken = token) => {
    if (!jwtToken) {
      logout(); // pas de token, on redirige
      return null;
    }

    setLoading(true); // loading activé pour fetchUser
    try {
      const { data } = await axiosClient.get("/dashboard", {
        headers: { Authorization: `Bearer ${jwtToken}` },
      });
      setUser(data);
      setLoading(false);
      return data;
    } catch (err) {
      setUser(null);
      setLoading(false);
      logout(); // token invalide → redirection vers login
      throw err;
    }
  };

  /**
   * Axios interceptors
   */
  useEffect(() => {
    const reqInterceptor = axiosClient.interceptors.request.use((config) => {
      if (token) config.headers["Authorization"] = `Bearer ${token}`;
      return config;
    });

    const resInterceptor = axiosClient.interceptors.response.use(
      (response) => response,
      async (error) => {
        const originalRequest = error.config;
        if (
          error.response?.status === 401 &&
          !originalRequest._retry
        ) {
          originalRequest._retry = true;
          try {
            const newToken = await refreshToken();
            originalRequest.headers["Authorization"] = `Bearer ${newToken}`;
            await fetchUser(newToken);
            return axiosClient(originalRequest);
          } catch {
            logout(); // si refresh échoue
          }
        }
        return Promise.reject(error);
      }
    );

    return () => {
      axiosClient.interceptors.request.eject(reqInterceptor);
      axiosClient.interceptors.response.eject(resInterceptor);
    };
  }, [token]);

  return (
    <AuthContext.Provider
      value={{
        token,
        user,
        loading,
        login,
        logout,
        fetchUser,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};
