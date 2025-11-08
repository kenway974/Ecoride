import { useState } from "react";

function RegisterForm() {
  const [email, setEmail] = useState("");
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");

  const handleRegister = async (e) => {
    e.preventDefault();

    try {
      const res = await fetch("http://localhost:8000/api/register", {
        method: "POST",
        headers: { "Content-Type": "application/json"
        },
        body: JSON.stringify({ email, username, password }),
      });

      // Récupérer la réponse brute pour debug
      const text = await res.text();

      let data;
      try {
        data = JSON.parse(text); // essaie de parser en JSON
      } catch {
        console.error("Réponse non JSON reçue :", text);
        setMessage("Erreur serveur ou réponse inattendue");
        return;
      }

      if (res.ok) {
        setMessage("Utilisateur créé avec succès ! Connecte-toi maintenant.");
        setEmail("");
        setUsername("");
        setPassword("");
      } else {
        setMessage(data.message || "Erreur lors de l'inscription");
      }
    } catch (err) {
      console.error("Erreur fetch :", err);
      setMessage("Erreur réseau ou serveur");
    }
  };

  return (
    <form onSubmit={handleRegister}>
      <input
        type="email"
        placeholder="Email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
        required
      />
      <input
        type="text"
        placeholder="Nom d'utilisateur"
        value={username}
        onChange={(e) => setUsername(e.target.value)}
        required
      />
      <input
        type="password"
        placeholder="Mot de passe"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        required
      />
      <button type="submit">S'inscrire</button>
      {message && <p>{message}</p>}
    </form>
  );
}

export default RegisterForm;
