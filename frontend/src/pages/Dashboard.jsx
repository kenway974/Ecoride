import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import VehicleForm from "../components/forms/VehicleForm";
import PreferenceForm from "../components/forms/PreferenceForm";
import NewTripForm from "../components/forms/NewTripForm";
import * as UserService from "../services/UserService";
import { createTrip } from "../services/TripService";


export default function Dashboard() {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);
  const [role, setRole] = useState(null);
  const [showVehicleForm, setShowVehicleForm] = useState(false);
  const [showTripForm, setShowTripForm] = useState(false);

  useEffect(() => {
    async function fetchUser() {
      try {
        const data = await UserService.getUserDashboard();

        setUser(data);
        setRole(data.preference?.role || null); 
        
      } catch (err) {
        console.error("Erreur fetch dashboard :", err);
        navigate("/login");
      }
    }

    fetchUser();
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

  const handleDriverClick = () => setShowVehicleForm(true);

  const handleVehicleSubmit = async (vehicleData) => {
    try {
      const newVehicle = await UserService.addVehicle(vehicleData);
      setUser((prev) => ({
        ...prev,
        vehicles: [...(prev.vehicles || []), newVehicle],
      }));
      await UserService.updateUserRole("chauffeur");
      setRole("chauffeur");
      setShowVehicleForm(false);
    } catch (err) {
      console.error(err);
      alert("Erreur lors de l’enregistrement du véhicule");
    }
  };

  const handlePreferencesSubmit = async (preferencesData) => {
    try {
      await UserService.savePreferences(preferencesData);
      alert("Préférences enregistrées !");
    } catch (err) {
      console.error(err);
      alert("Erreur lors de la sauvegarde des préférences");
    }
  };

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
            <p className="font-semibold">Bienvenue, {user.username || "Utilisateur"}</p>
            <p className="text-sm text-gray-500">
              Statut : {user.role || "Non défini"}
            </p>
          </div>
        </div>
      )}

      {/* Choix du rôle */}
      {!role && (
        <div className="flex gap-4 mb-6">
          <button
            onClick={handlePassengerClick}
            className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          >
            Je suis passager
          </button>
          <button
            onClick={handleDriverClick}
            className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
          >
            Je suis chauffeur
          </button>
        </div>
      )}

      {/* Formulaire chauffeur */}
      {showVehicleForm && <VehicleForm onSubmit={handleVehicleSubmit} />}

      {/* Contenu chauffeur */}
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

            {/* Boutons */}
            <div className="mt-3 flex gap-2">
              <button
                onClick={() => navigate("/trips")}
                className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
              >
                Voir tous les trajets
              </button>

              {/*role === "chauffeur" && (*/
                <button
                  onClick={() => setShowTripForm((prev) => !prev)}
                  className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                >
                  {showTripForm ? "Annuler" : "Ajouter un trajet"}
                </button>/*
              )*/}
            </div>

            {/* Formulaire nouveau trajet */}
            <NewTripForm
              vehicles={user?.vehicles || []}
              onSubmit={async (tripData) => {
                try {
                  const newTrip = await TripService.createTrip(tripData);
                  setUser(prev => ({ ...prev, trips: [...(prev.trips || []), newTrip] }));
                  setShowTripForm(false);
                } catch (err) {
                  console.error(err);
                  alert("Erreur lors de la création du trajet");
                }
              }}
            />

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
