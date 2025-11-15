import axios from "axios";
import axiosClient from "../AxiosClient";

const API_URL = "/api/user";

/**
 * update role
 * @param {string} role - "passager" | "chauffeur" | "passager_chauffeur"
 * @returns {Promise<Object>}
 */
export const updateUserRole = async (role) => {
  try {
    const response = await axios.put(`${API_URL}/role`, { role });
    return response.data;
  } catch (error) {
    console.error("Erreur updateUserRole:", error.response || error);
    throw error;
  }
};

/**
 * Ajoute un véhicule au user
 * @param {Object} vehicle
 * @returns {Promise<Object>}
 */
export const addVehicle = async (vehicle) => {
  try {
    const response = await axios.post(`${API_URL}/vehicles`, vehicle);
    return response.data;
  } catch (error) {
    console.error("Erreur addVehicle:", error.response || error);
    throw error;
  }
};

/**
 * Update preference user
 * @param {Object} preferences
 * @returns {Promise<Object>} 
 */
export const savePreferences = async (preferences) => {
  try {
    const response = await axios.put(`${API_URL}/preferences`, preferences);
    return response.data;
  } catch (error) {
    console.error("Erreur savePreferences:", error.response || error);
    throw error;
  }
};

export const getUserDashboard = async () => {
  const token = localStorage.getItem("token");
  try {
    const response = await axiosClient.get("/user_dashboard", {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    return response.data;
  } catch (error) {
    console.error("Erreur getUserDashboard:", error.response || error);
    throw error;
  }
};
