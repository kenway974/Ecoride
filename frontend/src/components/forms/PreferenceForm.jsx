import React, { useState } from "react";
import { savePreferences } from "../../services/UserService";

export default function PreferenceForm({ onPreferencesSaved }) {
  const [form, setForm] = useState({
    animals: false,
    smoke: false,
    food: false,
    is_custom: false,
    options: [],
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleChange = (e) => {
    const { name, type, checked, value } = e.target;
    setForm((prev) => ({
      ...prev,
      [name]: type === "checkbox" ? checked : value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const res = await savePreferences(form);
      onPreferencesSaved(res.data); // Callback pour Dashboard
    } catch (err) {
      setError(err.response?.data?.error || "Erreur serveur");
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4 border p-4 rounded shadow">
      <h3 className="text-lg font-semibold">Préférences</h3>

      {error && <p className="text-red-500">{error}</p>}

      <label className="flex items-center gap-2">
        <input type="checkbox" name="smoke" checked={form.smoke} onChange={handleChange} />
        Fumeur
      </label>
      <label className="flex items-center gap-2">
        <input type="checkbox" name="animals" checked={form.animals} onChange={handleChange} />
        Animaux
      </label>
      <label className="flex items-center gap-2">
        <input type="checkbox" name="food" checked={form.food} onChange={handleChange} />
        Nourriture
      </label>
      <label className="flex items-center gap-2">
        <input type="checkbox" name="is_custom" checked={form.is_custom} onChange={handleChange} />
        Ajouter des options personnalisées
      </label>

      {form.is_custom && (
        <input
          type="text"
          name="options"
          placeholder="Séparer les options par des virgules"
          onChange={(e) =>
            setForm((prev) => ({ ...prev, options: e.target.value.split(",") }))
          }
          className="border p-2 rounded w-full"
        />
      )}

      <button
        type="submit"
        disabled={loading}
        className="bg-blue-500 text-white px-4 py-2 rounded"
      >
        {loading ? "Enregistrement..." : "Enregistrer"}
      </button>
    </form>
  );
}
