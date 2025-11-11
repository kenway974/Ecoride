import axios from "../AxiosInstance";

const API_BASE = "/api"; // axiosInstance gère déjà http://localhost:8000

export const fetchTrips = async ({ from, to, date }) => {
  const params = {};
  if (from) params.from = from;
  if (to) params.to = to;
  if (date) params.date = date;

  const response = await axios.get(`${API_BASE}/trips`, { params });
  return response.data.data; // on retourne directement l'array
};

export const fetchTrip = async (id) => {
  const response = await axios.get(`${API_BASE}/trips/${id}`);
  return response.data.data; // idem, on retourne juste les données utiles
};
