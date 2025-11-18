import React, { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { fetchTrip, reserveTrip } from "../services/TripService"; // ajoute reserveTrip
import "./TripDetails.css";

export default function TripDetails() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [trip, setTrip] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [reserving, setReserving] = useState(false);
  const [reserveMessage, setReserveMessage] = useState(null);

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

  const handleReserve = async () => {
    if (!trip) return;

    setReserving(true);
    setReserveMessage(null);

    try {
      const result = await reserveTrip(trip.id);
      setReserveMessage("Réservation réussie !");
      setTrip(prev => ({ ...prev, seatsRemaining: prev.seatsRemaining - 1 }));
    } catch (err) {
      console.error(err);
      setReserveMessage(err.response?.data?.message || "Erreur lors de la réservation.");
    } finally {
      setReserving(false);
    }
  };

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

      {/* Bouton de réservation */}
      <button
        onClick={handleReserve}
        disabled={reserving || trip.seatsRemaining <= 0}
        className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-4"
      >
        {reserving ? "Réservation..." : trip.seatsRemaining > 0 ? "Réserver ce trajet" : "Complet"}
      </button>

      {reserveMessage && <p style={{ marginTop: "10px" }}>{reserveMessage}</p>}
    </div>
  );
}
