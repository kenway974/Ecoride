import axios from "axios";

const axiosClient = axios.create({
  baseURL: "http://localhost:8000/api",
  withCredentials: true, // important pour envoyer le cookie HttpOnly
  headers: {
    "Content-Type": "application/json",
  },
});

export default axiosClient;
