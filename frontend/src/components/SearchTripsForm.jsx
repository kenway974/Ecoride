// src/components/SearchTripForm.jsx
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import "./Form.css";

export default function SearchTripForm() {
  const [from, setFrom] = useState("");
  const [to, setTo] = useState("");
  const [date, setDate] = useState("");
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();
    // Redirection vers /trips avec query params
    navigate(
      `/trips?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&date=${encodeURIComponent(date)}`
    );
  };

  return (
    <form className="search-form" onSubmit={handleSubmit}>
      <div className="form-group">
        <label htmlFor="from">Ville de départ :</label>
        <input
          type="text"
          id="from"
          name="from"
          value={from}
          onChange={(e) => setFrom(e.target.value)}
          placeholder="Ex: Paris"
          required
        />
      </div>

      <div className="form-group">
        <label htmlFor="to">Ville d'arrivée :</label>
        <input
          type="text"
          id="to"
          name="to"
          value={to}
          onChange={(e) => setTo(e.target.value)}
          placeholder="Ex: Lyon"
          required
        />
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
