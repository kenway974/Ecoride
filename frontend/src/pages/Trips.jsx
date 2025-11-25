// src/pages/Trips.jsx
import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";
import { fetchTrips } from "../services/TripService";
import SearchTripsForm from "../components/SearchTripsForm";
import TripFilters from "../components/TripFilters";
import TripList from "../components/TripList";
import "./Trips.css";

export default function Trips() {
  const location = useLocation();

  // --- Etats principaux ---
  const [trips, setTrips] = useState([]);
  const [filteredTrips, setFilteredTrips] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [initial, setInitial] = useState(true);

  // --- Etats filtres ---
  const [filters, setFilters] = useState({
    driver: "",
    maxPrice: "",
    minSeats: "",
    ecological: false,
    animals: false,
    smoke: false,
    food: false,
  });

  // --- Query params ---
  const query = new URLSearchParams(location.search);
  const from = query.get("from");
  const to = query.get("to");
  const date = query.get("date");

  // --- Fetch trips ---
  useEffect(() => {
    if (!from && !to && !date) {
      setInitial(true);
      setTrips([]);
      setFilteredTrips([]);
      return;
    }

    setInitial(false);

    const loadTrips = async () => {
      try {
        setLoading(true);
        const response = await fetchTrips({ from, to, date });
        setTrips(response || []); 
        setFilteredTrips(response || []);
        setError(null);
      } catch (err) {
        setError(err.message || "Erreur lors de la récupération des trips.");
      } finally {
        setLoading(false);
      }
    };

    loadTrips();
  }, [from, to, date]);

  // --- Filtrage frontend ---
  useEffect(() => {
    let tmp = [...trips];

    if (filters.driver) {
      tmp = tmp.filter(trip =>
        trip.driver?.username.toLowerCase().includes(filters.driver.toLowerCase())
      );
    }
    if (filters.maxPrice) tmp = tmp.filter(trip => trip.price <= parseFloat(filters.maxPrice));
    if (filters.minSeats) tmp = tmp.filter(trip => trip.seatsRemaining >= parseInt(filters.minSeats));
    if (filters.ecological) tmp = tmp.filter(trip => trip.isEcological);

    // Preferences conducteur
    tmp = tmp.filter(trip => {
      const pref = trip.driver?.preference;
      if (!pref) return true;
      if (filters.animals && !pref.animals) return false;
      if (filters.smoke && !pref.smoke) return false;
      if (filters.food && !pref.food) return false;
      return true;
    });

    setFilteredTrips(tmp);
  }, [filters, trips]);

  return (
    <div className="trip-list">
      {initial && (
        <>
          <p>Entrez votre recherche pour afficher les trajets.</p>
          <SearchTripsForm />
        </>
      )}

      {loading && <p>Recherche de trajets en cours...</p>}
      {error && <p>Nous sommes navrés, ... {error}</p>}

      {!loading && trips.length > 0 && (
        <>
          {/* Nouvelle recherche */}
          <button
            onClick={() => {
              setInitial(true);
              setTrips([]);
              setFilteredTrips([]);
              setFilters({
                driver: "",
                maxPrice: "",
                minSeats: "",
                ecological: false,
                animals: false,
                smoke: false,
                food: false,
              });
            }}
            className="reset-button"
          >
            Nouvelle recherche
          </button>

          {/* Filtres */}
          <TripFilters filters={filters} setFilters={setFilters} />

          {/* Liste des trips */}
          <TripList trips={filteredTrips} />
        </>
      )}
    </div>
  );
}
