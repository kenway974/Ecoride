import { useState } from "react";
import { NavLink, Link } from "react-router-dom";
import "./Navbar.css"; // Styles séparés

function Navbar() {
  const [isOpen, setIsOpen] = useState(false);

  const toggleMenu = () => setIsOpen(!isOpen);

  return (
    <nav className="navbar">
      {/* Home */}
      <Link to="/" className="home">
        Home
      </Link>

      {/* Mobile burger */}
      <button className="burger" onClick={toggleMenu}>
        ☰
      </button>

      {/* Menu items */}
      <div className={`menu ${isOpen ? "open" : ""}`}>
        <NavLink to="/trips" className="nav-item">
          Trips
        </NavLink>
        <NavLink to="/contact" className="nav-item">
          Contact
        </NavLink>
      </div>

      {/* Connexion */}
      <Link to="/login" className="login">
        Connexion
      </Link>
    </nav>
  );
}

export default Navbar;
