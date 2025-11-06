// src/pages/Trips.jsx
import { useEffect, useState } from "react";
import { useLocation, Link } from "react-router-dom";
import { fetchTrips } from "../services/TripService";
import "./Trips.css";

export default function Trips() {
  const location = useLocation();
  const [trips, setTrips] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const query = new URLSearchParams(location.search);
  const from = query.get("from");
  const to = query.get("to");
  const date = query.get("date");

  useEffect(() => {
    const loadTrips = async () => {
      try {
        setLoading(true);
        const data = await fetchTrips({ from, to, date });
        setTrips(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };
    loadTrips();
  }, [from, to, date]);

  if (loading) return <p>Loading trips...</p>;
  if (error) return <p>Error: {error}</p>;
  if (trips.length === 0) return <p>No trips found.</p>;

  return (
    <div className="trip-list">
      {trips.map((trip) => (
        <div key={trip.id} className="trip-card">
          <h3>{trip.title}</h3>
          <p>{trip.description}</p>
          <p>
            {trip.from} → {trip.to} | {trip.date}
          </p>
          <Link to={`/trip/${trip.id}`}>View Details</Link>
        </div>
      ))}
    </div>
  );
}
