// src/services/axiosInstance.js
import axios from "axios";

// Crée une instance avec l'URL de base de ton backend
const instance = axios.create({
  baseURL: "http://localhost:8000", // à adapter selon ton environnement
  headers: {
    "Content-Type": "application/json",
  },
});

export default instance;
