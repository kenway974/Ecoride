import { useState } from "react";
import { useNavigate } from "react-router-dom";

function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();

    try {
      const res = await fetch("http://localhost:8000/api/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        // MODIF: assure-toi que l'API login renvoie { token: "..." } et pas autre chose
        body: JSON.stringify({ email, password })
      });

      // Récupérer la réponse brute pour debug si JSON invalide
      const text = await res.text(); 
      let data;
      try {
        data = JSON.parse(text);
      } catch {
        console.error("Réponse non JSON reçue :", text);
        setError("Erreur serveur ou réponse inattendue");
        return;
      }

      if (res.ok) {
        // Stocker le JWT dans localStorage pour les futures requêtes
        localStorage.setItem("token", data.token); 
        setError("");

        // Redirige vers page protégée
        navigate("/trips"); 
      } else {
        setError(data.message || "Identifiants incorrects");
      }
    } catch (err) {
      console.error("Erreur fetch :", err);
      setError("Erreur réseau ou serveur");
    }
  };

  return (
    <div>
      <h2>Connexion</h2>
      <form onSubmit={handleLogin}>
        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={e => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          placeholder="Mot de passe"
          value={password}
          onChange={e => setPassword(e.target.value)}
          required
        />
        <button type="submit">Se connecter</button>
      </form>
      {error && <p style={{ color: "red" }}>{error}</p>}
    </div>
  );
}

export default Login;
