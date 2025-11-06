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
    navigate(`/trips?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&date=${encodeURIComponent(date)}`);
  };

  return (
    <form className="search-form" onSubmit={handleSubmit}>
      <div>
        <label>From:</label>
        <input type="text" value={from} onChange={(e) => setFrom(e.target.value)} required />
      </div>
      <div>
        <label>To:</label>
        <input type="text" value={to} onChange={(e) => setTo(e.target.value)} required />
      </div>
      <div>
        <label>Date:</label>
        <input type="date" value={date} onChange={(e) => setDate(e.target.value)} required />
      </div>
      <button type="submit">Search</button>
    </form>
  );
}
