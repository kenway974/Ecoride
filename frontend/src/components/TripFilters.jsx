import { useState } from "react";

export default function TripFilters({ filters, setFilters }) {
  const [showFilters, setShowFilters] = useState(true);

  return (
    <div className="trip-filters">
      <button
        type="button"
        onClick={() => setShowFilters(!showFilters)}
        className="toggle-display-button"
      >
        {showFilters ? "Masquer les filtres" : "Afficher les filtres"}
      </button>

      {showFilters && (
        <>
          <h4>Filtrer les trajets</h4>

          <div>
            <label htmlFor="driver">Conducteur:</label>
            <input
              type="text"
              id="driver"
              value={filters.driver}
              onChange={e =>
                setFilters({ ...filters, driver: e.target.value })
              }
              placeholder="Nom du conducteur"
            />
          </div>

          <div>
            <label htmlFor="maxPrice">Prix max (€):</label>
            <input
              type="number"
              id="maxPrice"
              value={filters.maxPrice}
              onChange={e =>
                setFilters({ ...filters, maxPrice: e.target.value })
              }
            />
          </div>

          <div>
            <label htmlFor="minSeats">Places min:</label>
            <input
              type="number"
              id="minSeats"
              value={filters.minSeats}
              onChange={e =>
                setFilters({ ...filters, minSeats: e.target.value })
              }
            />
          </div>

          <div>
            <label htmlFor="ecological">Écologique:</label>
            <input
              type="checkbox"
              id="ecological"
              checked={filters.ecological}
              onChange={e =>
                setFilters({ ...filters, ecological: e.target.checked })
              }
            />
          </div>

          <div>
            <label htmlFor="animals">Animaux acceptés:</label>
            <input
              type="checkbox"
              id="animals"
              checked={filters.animals}
              onChange={e =>
                setFilters({ ...filters, animals: e.target.checked })
              }
            />
          </div>

          <div>
            <label htmlFor="smoke">Fumeur:</label>
            <input
              type="checkbox"
              id="smoke"
              checked={filters.smoke}
              onChange={e =>
                setFilters({ ...filters, smoke: e.target.checked })
              }
            />
          </div>

          <div>
            <label htmlFor="food">Nourriture:</label>
            <input
              type="checkbox"
              id="food"
              checked={filters.food}
              onChange={e =>
                setFilters({ ...filters, food: e.target.checked })
              }
            />
          </div>
        </>
      )}
    </div>
  );
}
