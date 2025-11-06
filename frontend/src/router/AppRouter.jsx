import { Routes, Route } from "react-router-dom";
import Home from "../pages/Home";
import Trips from "../pages/Trips";

function AppRouter() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/trips" element={<Trips />} />
    </Routes>
  );
}

export default AppRouter;
