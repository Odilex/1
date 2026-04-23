# Hotel Reservation System - React Frontend

A modern React frontend for the Hotel Reservation System with TailwindCSS styling.

## 🚀 Setup Instructions

### Prerequisites
- Node.js (v14 or higher)
- npm or yarn
- Laravel backend running on http://localhost:8000

### Installation

1. **Navigate to the frontend directory:**
   ```bash
   cd frontend
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

### Running the Development Server

1. **Start the React development server:**
   ```bash
   npm start
   ```
   or
   ```bash
   npm run dev
   ```

2. **Access the application:**
   Open your browser and go to: **http://localhost:3000**

## 🌐 Features

### Dashboard
- Real-time statistics (rooms, guests, reservations, revenue)
- Today's activity (check-ins, check-outs, occupancy rate)
- Recent reservations and payments feed
- Interactive visualizations and charts

### Rooms Management
- View all rooms with status indicators
- Room type, capacity, and pricing information
- Status-based color coding (available, occupied, maintenance)

### Guests Management
- Complete guest directory with contact information
- Guest details and reservation history
- Search and filter capabilities

### Reservations Management
- Comprehensive reservation listing
- Guest and room information
- Check-in/check-out dates and duration
- Status tracking (pending, confirmed, completed, cancelled)

### Payments Management
- Payment records with statistics
- Revenue tracking and averages
- Payment method categorization
- Reference number tracking

## 🎨 Design Features

- **Responsive Design** - Works on desktop, tablet, and mobile
- **Modern UI** - Clean, professional interface with TailwindCSS
- **Interactive Components** - Hover states, transitions, and animations
- **Color-coded Status** - Visual indicators for all status types
- **Navigation** - Active state indicators and intuitive menu structure

## 🔧 Technology Stack

- **React 18** - Modern React with hooks
- **React Router** - Client-side routing
- **TailwindCSS** - Utility-first CSS framework
- **Heroicons** - Professional icon library
- **Axios** - HTTP client for API requests
- **Webpack** - Module bundler and development server

## 📡 API Integration

The React frontend communicates with the Laravel backend through RESTful APIs:

- `GET /api/dashboard` - Dashboard statistics and recent activity
- `GET /api/rooms` - Rooms list
- `GET /api/guests` - Guests list
- `GET /api/reservations` - Reservations list
- `GET /api/payments` - Payments list with statistics

## 🔄 Development Workflow

1. **Backend Setup:**
   - Ensure Laravel backend is running on port 8000
   - Database migrations and seeders should be executed

2. **Frontend Development:**
   - Run `npm start` for development server
   - Hot reload enabled for rapid development
   - API proxy configured to avoid CORS issues

3. **Production Build:**
   ```bash
   npm run build
   ```
   - Optimized production build in `/dist` folder

## 🎯 Navigation

- **Dashboard** - Overview with statistics and activity feeds
- **Rooms** - Room management and status tracking
- **Guests** - Guest registration and management
- **Reservations** - Booking management and calendar
- **Payments** - Financial tracking and payment records

## 📱 Responsive Design

The application is fully responsive with:
- Mobile-first design approach
- Adaptive layouts for different screen sizes
- Touch-friendly interface elements
- Optimized navigation for mobile devices

## 🚨 Troubleshooting

### Common Issues

1. **API Connection Errors:**
   - Ensure Laravel backend is running on port 8000
   - Check that API routes are properly configured

2. **CORS Issues:**
   - The webpack proxy is configured to handle CORS
   - Verify proxy settings in webpack.config.js

3. **Styling Issues:**
   - Ensure TailwindCSS is properly configured
   - Check that PostCSS is processing Tailwind directives

4. **Build Errors:**
   - Clear node_modules and reinstall: `rm -rf node_modules && npm install`
   - Check for any missing dependencies in package.json

## 🔄 Future Enhancements

- Real-time updates with WebSockets
- Advanced search and filtering
- Data visualization charts
- Export functionality (PDF, Excel)
- Mobile app version
- Multi-language support
- Dark mode toggle
