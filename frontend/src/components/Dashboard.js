import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { 
  BuildingOfficeIcon, 
  UserGroupIcon, 
  CalendarDaysIcon, 
  CurrencyDollarIcon 
} from '@heroicons/react/24/outline';

const Dashboard = () => {
  const [stats, setStats] = useState({
    totalRooms: 0,
    totalGuests: 0,
    totalReservations: 0,
    totalRevenue: 0,
    availableRooms: 0,
    occupiedRooms: 0,
    occupancyRate: 0,
    todayCheckins: 0,
    todayCheckouts: 0,
  });
  const [recentReservations, setRecentReservations] = useState([]);
  const [recentPayments, setRecentPayments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      const response = await axios.get('/api/dashboard');
      setStats(response.data.stats);
      setRecentReservations(response.data.recentReservations);
      setRecentPayments(response.data.recentPayments);
    } catch (error) {
      console.error('Error fetching dashboard data:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="text-lg text-gray-600">Loading dashboard...</div>
      </div>
    );
  }

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-semibold text-gray-900">Serene Heights Hotel Dashboard</h1>
        <p className="mt-1 text-sm text-gray-600">Welcome to your hotel management dashboard.</p>
      </div>

      {/* Key Statistics Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div className="bg-white p-6 rounded-lg shadow">
          <div className="flex items-center">
            <div className="flex-shrink-0 bg-blue-100 rounded-md p-3">
              <BuildingOfficeIcon className="h-6 w-6 text-blue-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-500">Total Rooms</p>
              <p className="text-2xl font-semibold text-gray-900">{stats.totalRooms}</p>
              <div className="flex space-x-2 mt-1">
                <span className="text-xs text-green-600">{stats.availableRooms} available</span>
                <span className="text-xs text-red-600">{stats.occupiedRooms} occupied</span>
              </div>
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow">
          <div className="flex items-center">
            <div className="flex-shrink-0 bg-green-100 rounded-md p-3">
              <UserGroupIcon className="h-6 w-6 text-green-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-500">Total Guests</p>
              <p className="text-2xl font-semibold text-gray-900">{stats.totalGuests}</p>
              <p className="text-xs text-gray-500 mt-1">Registered guests</p>
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow">
          <div className="flex items-center">
            <div className="flex-shrink-0 bg-yellow-100 rounded-md p-3">
              <CalendarDaysIcon className="h-6 w-6 text-yellow-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-500">Total Reservations</p>
              <p className="text-2xl font-semibold text-gray-900">{stats.totalReservations}</p>
              <p className="text-xs text-gray-500 mt-1">All time</p>
            </div>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow">
          <div className="flex items-center">
            <div className="flex-shrink-0 bg-purple-100 rounded-md p-3">
              <CurrencyDollarIcon className="h-6 w-6 text-purple-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-500">Total Revenue</p>
              <p className="text-2xl font-semibold text-gray-900">RWF {stats.totalRevenue.toLocaleString()}</p>
              <p className="text-xs text-gray-500 mt-1">All time</p>
            </div>
          </div>
        </div>
      </div>

      {/* Today's Activity & Occupancy */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {/* Today's Activity */}
        <div className="bg-white p-6 rounded-lg shadow">
          <h3 className="text-lg font-medium text-gray-900 mb-4">Today's Activity</h3>
          <div className="space-y-3">
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-500">Check-ins</span>
              <span className="text-lg font-semibold text-green-600">{stats.todayCheckins}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-500">Check-outs</span>
              <span className="text-lg font-semibold text-blue-600">{stats.todayCheckouts}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-500">Occupancy Rate</span>
              <span className="text-lg font-semibold text-purple-600">{stats.occupancyRate}%</span>
            </div>
          </div>
        </div>

        {/* Recent Reservations */}
        <div className="bg-white shadow rounded-lg">
          <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 className="text-lg leading-6 font-medium text-gray-900">Recent Reservations</h3>
          </div>
          <div className="overflow-hidden">
            {recentReservations.length > 0 ? (
              recentReservations.map((reservation) => (
                <div key={reservation.id} className="px-4 py-4 sm:px-6 border-b border-gray-200">
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="text-sm font-medium text-gray-900">{reservation.guest_name}</p>
                      <p className="text-sm text-gray-500">Room {reservation.room_number}</p>
                    </div>
                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      reservation.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                      reservation.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-gray-100 text-gray-800'
                    }`}>
                      {reservation.status}
                    </span>
                  </div>
                </div>
              ))
            ) : (
              <div className="px-4 py-8 text-center text-gray-500">
                No recent reservations
              </div>
            )}
          </div>
        </div>

        {/* Recent Payments */}
        <div className="bg-white shadow rounded-lg">
          <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 className="text-lg leading-6 font-medium text-gray-900">Recent Payments</h3>
          </div>
          <div className="overflow-hidden">
            {recentPayments.length > 0 ? (
              recentPayments.map((payment) => (
                <div key={payment.id} className="px-4 py-4 sm:px-6 border-b border-gray-200">
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="text-sm font-medium text-gray-900">{payment.guest_name}</p>
                      <p className="text-sm text-gray-500">Room {payment.room_number}</p>
                    </div>
                    <div className="text-right">
                      <p className="text-sm font-bold text-green-600">RWF {payment.amount.toLocaleString()}</p>
                      <p className="text-xs text-gray-500">{payment.method}</p>
                    </div>
                  </div>
                </div>
              ))
            ) : (
              <div className="px-4 py-8 text-center text-gray-500">
                No recent payments
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
