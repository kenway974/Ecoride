import axios from "../AxiosClient";

const API_BASE = "/api";

// Récupérer plusieurs trajets
export const fetchTrips = async ({ from, to, date }) => {
  const params = {};
  if (from) params.from = from;
  if (to) params.to = to;
  if (date) params.date = date;

  const response = await axios.get(`${API_BASE}/trips`, { params });
  return response.data.data;
};

// Récupérer un trajet par son ID
export const fetchTrip = async (id) => {
  const response = await axios.get(`${API_BASE}/trips/${id}`);
  return response.data.data;
};

// Créer un nouveau trajet
export const createTrip = async (tripData) => {
  const response = await axios.post(`${API_BASE}/trip`, tripData);
  return response.data.data;
};

export const reserveTrip = async (id) => {
  const response = await axios.post(`/api/trips/${id}/reserve`);
  return response.data;
};
