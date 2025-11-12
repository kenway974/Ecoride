import React, { useState, useEffect } from "react";
import VehicleForm from "../components/forms/VehicleForm";
import PreferenceForm from "../components/forms/PreferenceForm";
import * as UserService from "../services/UserService"; // import du service complet

export default function Dashboard() {
  const [role, setRole] = useState(null);
  const [user, setUser] = useState(null);
  const [showVehicleForm, setShowVehicleForm] = useState(false);

  // 👤 récupération de l'utilisateur mockée pour l'instant
  useEffect(() => {
    const mockUser = {
      id: 1,
      nom: "Alex",
      photo: "https://via.placeholder.com/100",
      role: null,
      vehicles: [
        { id: 1, marque: "Peugeot", modele: "208", plaque: "AB-123-CD" },
        { id: 2, marque: "Renault", modele: "Clio", plaque: "XY-456-ZZ" },
      ],
      trips: [
        { id: 1, depart: "Lyon", arrivee: "Paris", date: "2025-11-15" },
        { id: 2, depart: "Marseille", arrivee: "Nice", date: "2025-11-20" },
      ],
      reservations: [
        { id: 1, trajet: "Paris → Lille", date: "2025-11-13" },
      ],
      reviews: [
        { id: 1, auteur: "Paul", note: 5, commentaire: "Super chauffeur !" },
        { id: 2, auteur: "Lucie", note: 4, commentaire: "Très sympa !" },
      ],
    };
    setUser(mockUser);
    setRole(mockUser.role);
  }, []);

  // ---------------------
  // Gestion des rôles
  // ---------------------
  const handlePassengerClick = async () => {
    try {
      await UserService.updateUserRole("passager");
      setRole("passager");
      setUser((prev) => ({ ...prev, role: "passager" }));
    } catch (err) {
      console.error(err);
      alert("Erreur lors de la mise à jour du rôle passager");
    }
  };

  const handleDriverClick = () => {
    setShowVehicleForm(true); // toggle le formulaire
  };

  const handleVehicleSubmit = async (vehicleData) => {
    try {
      const newVehicle = await UserService.addVehicle(vehicleData);

      // Ajout du véhicule dans le state local
      setUser((prev) => ({
        ...prev,
        vehicles: [...(prev.vehicles || []), newVehicle],
      }));

      // Mise à jour du rôle en "chauffeur"
      await UserService.updateUserRole("chauffeur");
      setRole("chauffeur");

      // On cache le formulaire
      setShowVehicleForm(false);
    } catch (err) {
      console.error(err);
      alert("Erreur lors de l’enregistrement du véhicule");
    }
  };

  // ---------------------
  // Gestion des préférences
  // ---------------------
  const handlePreferencesSubmit = async (preferencesData) => {
    try {
      await UserService.savePreferences(preferencesData);
      alert("Préférences enregistrées !");
    } catch (err) {
      console.error(err);
      alert("Erreur lors de la sauvegarde des préférences");
    }
  };

  // ---------------------
  // Rendu
  // ---------------------
  return (
    <div className="p-6">
      <h2 className="text-2xl font-semibold mb-4">Mon Espace</h2>

      {/* Profil utilisateur */}
      {user && (
        <div className="mb-6 flex items-center gap-4">
          {user.photo && (
            <img
              src={user.photo}
              alt="Profil"
              className="w-20 h-20 rounded-full object-cover shadow-md"
            />
          )}
          <div>
            <p className="font-semibold">Bienvenue, {user.nom || "Utilisateur"}</p>
            <p className="text-sm text-gray-500">
              Statut : {role || "Non défini"}
            </p>
          </div>
        </div>
      )}

      {/* Choix du rôle */}
      {!role && (
        <div className="flex gap-4 mb-6">
          <button
            onClick={handlePassengerClick}
            className="bg-blue-500 text-white px-4 py-2 rounded"
          >
            Je suis passager
          </button>
          <button
            onClick={handleDriverClick}
            className="bg-green-500 text-white px-4 py-2 rounded"
          >
            Je suis chauffeur
          </button>
        </div>
      )}

      {/* Formulaire chauffeur */}
      {showVehicleForm && (
        <div className="mt-6">
          <VehicleForm onSubmit={handleVehicleSubmit} />
        </div>
      )}

      {role === "chauffeur" && (
        <div className="mt-6 space-y-6">
          <PreferenceForm onSubmit={handlePreferencesSubmit} />

          {/* Véhicules */}
          {user?.vehicles?.length > 0 && (
            <div>
              <h3 className="font-semibold mb-2">Mes véhicules :</h3>
              <ul className="list-disc ml-5">
                {user.vehicles.map((v) => (
                  <li key={v.id}>
                    {v.marque} {v.modele} — {v.plaque}
                  </li>
                ))}
              </ul>
            </div>
          )}
        </div>
      )}

      {/* Passager simple */}
      {role === "passager" && (
        <p className="mt-4 text-gray-600">
          Aucune info supplémentaire requise ✅
        </p>
      )}

      {/* Infos utilisateur générales */}
      {user && (
        <div className="mt-10 space-y-8">
          {/* Trajets */}
          <section>
            <h3 className="text-xl font-semibold mb-2">Mes trajets</h3>
            {user.trips?.length > 0 ? (
              <ul className="list-disc ml-5 text-gray-700">
                {user.trips.map((t) => (
                  <li key={t.id}>
                    {t.depart} → {t.arrivee} ({t.date})
                  </li>
                ))}
              </ul>
            ) : (
              <p className="text-gray-500">Aucun trajet pour le moment.</p>
            )}
          </section>

          {/* Réservations */}
          <section>
            <h3 className="text-xl font-semibold mb-2">Mes réservations</h3>
            {user.reservations?.length > 0 ? (
              <ul className="list-disc ml-5 text-gray-700">
                {user.reservations.map((r) => (
                  <li key={r.id}>
                    {r.trajet} — {r.date}
                  </li>
                ))}
              </ul>
            ) : (
              <p className="text-gray-500">Aucune réservation active.</p>
            )}
          </section>

          {/* Avis */}
          <section>
            <h3 className="text-xl font-semibold mb-2">Mes avis</h3>
            {user.reviews?.length > 0 ? (
              <ul className="list-disc ml-5 text-gray-700">
                {user.reviews.map((rev) => (
                  <li key={rev.id}>
                    <span className="font-medium">{rev.auteur}</span> ({rev.note}/5) —{" "}
                    {rev.commentaire}
                  </li>
                ))}
              </ul>
            ) : (
              <p className="text-gray-500">Aucun avis pour le moment.</p>
            )}
          </section>
        </div>
      )}
    </div>
  );
}
