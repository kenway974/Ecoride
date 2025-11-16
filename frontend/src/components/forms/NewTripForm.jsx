import { useState } from "react";

export default function NewTripForm({ vehicles = [], onSubmit }) {
  const [formData, setFormData] = useState({
    vehicleId: vehicles[0]?.id || "",
    startCity: "",
    arrivalCity: "",
    startAddress: "",
    arrivalAddress: "",
    departureDate: "",
    departureTime: "",
    arrivalDate: "",
    arrivalTime: "",
    seatsRemaining: 1,
    price: 0,
    isEcological: false,
    description: "",
    luggage: "",
  });

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: type === "checkbox" ? checked : value,
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault(); // bloque le rechargement
    if (onSubmit) onSubmit(formData); // appelle la fonction du parent
  };

  return (
    <form onSubmit={handleSubmit} className="bg-gray-50 p-4 rounded shadow-md space-y-4">
      {/* Le reste du formulaire reste inchangé */}
      {/* Select véhicule */}
      <div>
        <label className="block font-medium mb-1">Véhicule</label>
        <select
          name="vehicleId"
          value={formData.vehicleId}
          onChange={handleChange}
          className="w-full border px-2 py-1 rounded"
          required
        >
          {vehicles.map((v) => (
            <option key={v.id} value={v.id}>
              {v.marque} {v.modele} — {v.plaque}
            </option>
          ))}
        </select>
      </div>

      {/* Les autres champs… startCity, arrivalCity, etc. */}
      {/* ... */}
      
      <button
        type="submit"
        className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
      >
        Créer le trajet
      </button>
    </form>
  );
}
