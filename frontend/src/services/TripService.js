const API_BASE = "http://localhost:8000/api"; // adapte selon ton Symfony

export const fetchTrips = async ({ from, to, date }) => {
  const params = new URLSearchParams({ from, to, date });
  const res = await fetch(`${API_BASE}/trips?${params.toString()}`);
  if (!res.ok) throw new Error("Failed to fetch trips");
  return res.json();
};

export const fetchTripById = async (id) => {
  const res = await fetch(`${API_BASE}/trips/${id}`);
  if (!res.ok) throw new Error("Failed to fetch trip details");
  return res.json();
};
