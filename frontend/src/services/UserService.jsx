import axios from "axios";

const API_URL = "/api";

export const updateUserRole = (role) =>
  axios.put(`${API_URL}/user/role`, { role });

export const addVehicle = (vehicle) =>
  axios.post(`${API_URL}/user/vehicles`, vehicle);

export const savePreferences = (preferences) =>
  axios.put(`${API_URL}/user/preferences`, preferences);
