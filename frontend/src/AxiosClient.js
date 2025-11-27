import axios from "axios";

const axiosClient = axios.create({
  baseURL: "https://ecoride-backend-qtb1.onrender.com",
  headers: {
    "Content-Type": "application/json",
  },
});

export default axiosClient;
