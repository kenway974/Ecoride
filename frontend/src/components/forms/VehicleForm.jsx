import React, { useState } from "react";
import { addVehicle } from "../../services/UserService";

export default function VehicleForm({ onVehicleAdded }) {
  const [form, setForm] = useState({
    plate: "",
    brand: "",
    model: "",
    release_year: "",
    energy: "",
    seats_total: "",
    seats_available: "",
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const res = await addVehicle({
        ...form,
        release_year: parseInt(form.release_year),
        seats_total: parseInt(form.seats_total),
        seats_available: parseInt(form.seats_available),
      });

      onVehicleAdded(res.data); // Callback pour mettre à jour Dashboard
      setForm({
        plate: "",
        brand: "",
        model: "",
        release_year: "",
        energy: "",
        seats_total: "",
        seats_available: "",
      });
    } catch (err) {
      setError(err.response?.data?.error || "Erreur serveur");
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4 border p-4 rounded shadow">
      <h3 className="text-lg font-semibold">Ajouter un véhicule</h3>

      {error && <p className="text-red-500">{error}</p>}

      <input
        type="text"
        name="plate"
        placeholder="Plaque"
        value={form.plate}
        onChange={handleChange}
        className="border p-2 rounded w-full"
        required
      />
      <input
        type="text"
        name="brand"
        placeholder="Marque"
        value={form.brand}
        onChange={handleChange}
        className="border p-2 rounded w-full"
        required
      />
      <input
        type="text"
        name="model"
        placeholder="Modèle"
        value={form.model}
        onChange={handleChange}
        className="border p-2 rounded w-full"
        required
      />
      <input
        type="number"
        name="release_year"
        placeholder="Année"
        value={form.release_year}
        onChange={handleChange}
        className="border p-2 rounded w-full"
        required
      />
      <input
        type="text"
        name="energy"
        placeholder="Énergie"
        value={form.energy}
        onChange={handleChange}
        className="border p-2 rounded w-full"
        required
      />
      <input
        type="number"
        name="seats_total"
        placeholder="Nombre total de places"
        value={form.seats_total}
        onChange={handleChange}
        className="border p-2 rounded w-full"
        required
      />
      <input
        type="number"
        name="seats_available"
        placeholder="Nombre de places disponibles"
        value={form.seats_available}
        onChange={handleChange}
        className="border p-2 rounded w-full"
        required
      />

      <button
        type="submit"
        disabled={loading}
        className="bg-green-500 text-white px-4 py-2 rounded"
      >
        {loading ? "Enregistrement..." : "Ajouter"}
      </button>
    </form>
  );
}
