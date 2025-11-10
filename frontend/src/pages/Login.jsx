import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../contexts/AuthContext"; // <- AuthContext
// plus besoin d'importer axiosClient ici, c'est géré dans le contexte

function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();
  const { login } = useAuth(); // <- récupère la fonction login depuis le contexte

  const handleLogin = async (e) => {
    e.preventDefault();

    try {
      await login(email, password); // géré par AuthContext, stocke le JWT
      setError("");
      navigate("/trips"); // redirige vers la page protégée
    } catch (err) {
      console.error(err);
      if (err.response && err.response.data) {
        setError(err.response.data.message || "Identifiants incorrects");
      } else {
        setError("Erreur réseau ou serveur");
      }
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
