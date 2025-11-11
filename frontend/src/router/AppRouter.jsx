import { Routes, Route } from "react-router-dom";
import Home from "../pages/Home";
import Trips from "../pages/Trips";
import TripDetails from "../pages/TripDetails";

function AppRouter() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/trips" element={<Trips />} />
      <Route path="/trips" element={<Trips />} />
      <Route path="/trip/:id" element={<TripDetails />} />
    </Routes>
  );
}

export default AppRouter;
