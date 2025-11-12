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
    e.preventDefault();
    if (onSubmit) onSubmit(formData);
  };

  return (
    <form onSubmit={handleSubmit} className="bg-gray-50 p-4 rounded shadow-md space-y-4">
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

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label className="block font-medium mb-1">Ville de départ</label>
          <input
            type="text"
            name="startCity"
            value={formData.startCity}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
            required
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Ville d'arrivée</label>
          <input
            type="text"
            name="arrivalCity"
            value={formData.arrivalCity}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
            required
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Adresse de départ</label>
          <input
            type="text"
            name="startAddress"
            value={formData.startAddress}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
            required
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Adresse d'arrivée</label>
          <input
            type="text"
            name="arrivalAddress"
            value={formData.arrivalAddress}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
            required
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Date de départ</label>
          <input
            type="date"
            name="departureDate"
            value={formData.departureDate}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
            required
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Heure de départ</label>
          <input
            type="time"
            name="departureTime"
            value={formData.departureTime}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
            required
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Date d'arrivée</label>
          <input
            type="date"
            name="arrivalDate"
            value={formData.arrivalDate}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Heure d'arrivée</label>
          <input
            type="time"
            name="arrivalTime"
            value={formData.arrivalTime}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Places disponibles</label>
          <input
            type="number"
            name="seatsRemaining"
            value={formData.seatsRemaining}
            min={1}
            max={8}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
            required
          />
        </div>
        <div>
          <label className="block font-medium mb-1">Prix (€)</label>
          <input
            type="number"
            name="price"
            value={formData.price}
            min={1}
            max={100}
            onChange={handleChange}
            className="w-full border px-2 py-1 rounded"
            required
          />
        </div>
      </div>

      <div className="flex items-center gap-2">
        <input
          type="checkbox"
          name="isEcological"
          checked={formData.isEcological}
          onChange={handleChange}
        />
        <label className="font-medium">Trajet écologique</label>
      </div>

      <div>
        <label className="block font-medium mb-1">Description</label>
        <textarea
          name="description"
          value={formData.description}
          onChange={handleChange}
          className="w-full border px-2 py-1 rounded"
          rows={3}
        />
      </div>

      <div>
        <label className="block font-medium mb-1">Bagages</label>
        <textarea
          name="luggage"
          value={formData.luggage}
          onChange={handleChange}
          className="w-full border px-2 py-1 rounded"
          rows={2}
        />
      </div>

      <button
        type="submit"
        className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
      >
        Créer le trajet
      </button>
    </form>
  );
}
