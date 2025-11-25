import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { cities } from "../data/cities";
import "./Form.css";

export default function SearchTripForm() {
  const [from, setFrom] = useState("");
  const [to, setTo] = useState("");
  const [date, setDate] = useState("");
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();

    if (!from || !to) {
      alert("Veuillez sélectionner les villes de départ et d'arrivée.");
      return;
    }

    navigate(
      `/trips?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&date=${encodeURIComponent(date)}`
    );
  };

  return (
    <form className="search-form" onSubmit={handleSubmit}>
      <div className="form-group">
        <label htmlFor="from">Ville de départ :</label>
        <select
          id="from"
          name="from"
          value={from}
          onChange={(e) => setFrom(e.target.value)}
          required
        >
          <option value="">-- Choisissez une ville --</option>
          {cities.map((city) => (
            <option key={city} value={city}>
              {city}
            </option>
          ))}
        </select>
      </div>

      <div className="form-group">
        <label htmlFor="to">Ville d'arrivée :</label>
        <select
          id="to"
          name="to"
          value={to}
          onChange={(e) => setTo(e.target.value)}
          required
        >
          <option value="">-- Choisissez une ville --</option>
          {cities.map((city) => (
            <option key={city} value={city}>
              {city}
            </option>
          ))}
        </select>
      </div>

      <div className="form-group">
        <label htmlFor="date">Date du trajet :</label>
        <input
          type="date"
          id="date"
          name="date"
          value={date}
          onChange={(e) => setDate(e.target.value)}
        />
      </div>

      <button type="submit" className="search-button">
        Rechercher
      </button>
    </form>
  );
}
