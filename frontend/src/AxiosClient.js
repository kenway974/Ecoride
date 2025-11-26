import axios from "axios";

const axiosClient = axios.create({
  baseURL: "https://ecoride-9r0a:9000/api",
  headers: {
    "Content-Type": "application/json",
  },
});

export default axiosClient;
