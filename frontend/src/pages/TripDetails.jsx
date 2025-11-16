import React, { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { fetchTrip } from "../services/TripService";
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
      <button onClick={() => navigate(-1)} className="back-button">Retour</button>

      <h2>Trajet #{trip.id}</h2>
      <p><strong>De :</strong> {trip.startCity}</p>
      <p><strong>À :</strong> {trip.arrivalCity}</p>
      <p><strong>Date départ :</strong> {trip.departureDate ? new Date(trip.departureDate).toLocaleDateString() : "N/A"}</p>
      <p><strong>Heure départ :</strong> {trip.departureTime ?? "N/A"}</p>
      <p><strong>Date arrivée :</strong> {trip.arrivalDate ? new Date(trip.arrivalDate).toLocaleDateString() : "N/A"}</p>
      <p><strong>Heure arrivée :</strong> {trip.arrivalTime ?? "N/A"}</p>
      <p><strong>Places restantes :</strong> {trip.seatsRemaining}</p>
      <p><strong>Prix :</strong> {trip.price} €</p>
      <p><strong>Écologique :</strong> {trip.isEcological ? "Oui" : "Non"}</p>
      <p><strong>Status :</strong> {trip.status}</p>

      {trip.driver && (
        <>
          <p><strong>Conducteur :</strong> {trip.driver.username}</p>

          {trip.driver.preference && (
            <p><strong>Préférences :</strong> {JSON.stringify(trip.driver.preference)}</p>
          )}

          {trip.driver.vehicles && trip.driver.vehicles.length > 0 && (
            <div>
              <strong>Véhicules du conducteur :</strong>
              <ul>
                {trip.driver.vehicles.map((v, idx) => (
                  <li key={idx}>{v.brand} {v.model} ({v.seatsTotal} places)</li>
                ))}
              </ul>
            </div>
          )}
        </>
      )}

      {trip.vehicle && trip.vehicle.length > 0 && (
        <div>
          <strong>Véhicule du trip :</strong>
          <ul>
            {trip.vehicle.map((v, idx) => (
              <li key={idx}>{v.brand} {v.model} ({v.seatsTotal} places)</li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
}
