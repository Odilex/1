import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import Dashboard from './components/Dashboard';
import RoomsList from './components/Rooms/RoomsList';
import GuestsList from './components/Guests/GuestsList';
import ReservationsList from './components/Reservations/ReservationsList';
import PaymentsList from './components/Payments/PaymentsList';

function App() {
  return (
    <Router>
      <div className="min-h-screen bg-gray-50">
        <Navbar />
        <main className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
          <Routes>
            <Route path="/" element={<Dashboard />} />
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/rooms" element={<RoomsList />} />
            <Route path="/guests" element={<GuestsList />} />
            <Route path="/reservations" element={<ReservationsList />} />
            <Route path="/payments" element={<PaymentsList />} />
          </Routes>
        </main>
      </div>
    </Router>
  );
}

export default App;
