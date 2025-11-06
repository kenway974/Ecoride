import SearchTripForm from "../components/SearchTripsForm";
import "./Home.css";

export default function Home() {
  return (
    <div className="home-container">
      <h1 className="home-title">Covoiturage facile</h1>

      <section className="info-section">
        <div className="info-row">
          <div className="info-image placeholder-img"></div>
          <p className="info-text">
            Que tu sois conducteur ou passager, tu trouveras des personnes
            qui partagent ton trajet.
          </p>
        </div>

        <div className="info-row reverse">
          <p className="info-text">
            Rend tes déplacements plus économiques et plus écologiques.
          </p>
          <div className="info-image placeholder-img"></div>
        </div>
      </section>

      <p className="cta-text">Et tout ça en un clic !</p>

      {/* Ici on intègre directement le formulaire réutilisable */}
      <SearchTripForm />

      <footer className="footer">© 2025 MonSiteCovoiturage</footer>
    </div>
  );
}
