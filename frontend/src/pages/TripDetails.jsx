import React, { useState, useEffect } from "react"; // useState et useEffect
import { useParams, useNavigate } from "react-router-dom"; // hooks router
import { fetchTrip } from "../services/TripService"; // ton service
import "./TripDetails.css";


export default function TripDetails() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [trip, setTrip] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const loadTrip = async () => {
      try {
        const data = await fetchTrip(id);
        setTrip(data);
      } catch (err) {
        setError("Impossible de récupérer ce trajet.");
      } finally {
        setLoading(false);
      }
    };

    loadTrip();
  }, [id]);

  if (loading) return <p>Chargement...</p>;
  if (error) return <p style={{ color: "red" }}>{error}</p>;
  if (!trip) return <p>Trajet introuvable.</p>;

  return (
    <div className="trip-details">
      <button onClick={() => navigate(-1)} className="back-button">
        Retour
      </button>

      <h2>Trajet #{trip.id}</h2>
      <p><strong>De :</strong> {trip.start_city}</p>
      <p><strong>À :</strong> {trip.arrival_city}</p>
      <p><strong>Date :</strong> {new Date(trip.start_date).toLocaleDateString()}</p>
      <p><strong>Date :</strong> {new Date(trip.departure_time).toLocaleDateString()}</p>
      <p><strong>Date :</strong> {new Date(trip.arrival_date).toLocaleDateString()}</p>
      <p><strong>Date :</strong> {new Date(trip.arrival_date).toLocaleDateString()}</p>
      <p><strong>Prix :</strong> {trip.price} €</p>
      <p><strong>Places restantes :</strong> {trip.seats_remaining}</p>

      {trip.driver && (
        <>
          <p><strong>Conducteur :</strong> {trip.driver.username}</p>
          {trip.driver.preference && (
            <p><strong>Préférences :</strong> {JSON.stringify(trip.driver.preference)}</p>
          )}
        </>
      )}

      {trip.vehicle && (
        <p><strong>Véhicule :</strong> {trip.vehicle.brand} {trip.vehicle.model}</p>
      )}
    </div>
  );
}
