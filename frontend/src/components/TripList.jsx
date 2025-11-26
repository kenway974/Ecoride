// src/components/TripList.jsx
import { Link } from "react-router-dom";

export default function TripList({ trips }) {
  {trips.length > 0 &&
        date &&
        trips.some(trip => trip.departureDate !== date) && (
          <p>Nous vous proposons des trajets proches de la date recherchée :</p>
        )}

  return trips.map(trip => (
    <div key={trip.id} className="trip-card">
      <h3>{trip.title || "Sans titre"}</h3>
      <p>{trip.description || "Pas de description"}</p>
      <p>
        {trip.startCity || "Ville départ inconnue"} →{" "}
        {trip.arrivalCity || "Ville arrivée inconnue"} |{" "}
        {trip.departureDate
          ? new Date(trip.departureDate).toLocaleDateString()
          : "Date inconnue"}
      </p>

      {trip.driver && (
        <div className="driver-info">
          <p>Conducteur : {trip.driver.username}</p>
          {trip.driver.photo && (
            <img
              src={trip.driver.photo}
              alt={trip.driver.username}
              className="driver-photo"
            />
          )}
        </div>
      )}

      <p>Places restantes : {trip.seatsRemaining ?? "N/A"}</p>
      <p>Prix : {trip.price ?? "N/A"} €</p>
      <p>
        Départ : {trip.departureTime ?? "N/A"}, Arrivée : {trip.arrivalTime ?? "N/A"}
      </p>
      <p>Écologique : {trip.isEcological ? "Oui" : "Non"}</p>

      <Link to={`/trip/${trip.id}`}>View Details</Link>
    </div>
  ));
}
