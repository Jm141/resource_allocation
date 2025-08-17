# Enhanced Map Features - Resource Allocation System

## Overview
The live map has been enhanced with advanced features including incident visualization, deployment tracking, and intelligent route optimization to avoid problematic areas.

## New Features

### 1. Incident Pins on Map
- **Real-time Display**: Active incidents are shown as colored pins on the map
- **Priority-based Colors**:
  - 🔴 **Critical**: Red (highest priority)
  - 🟠 **High**: Orange (urgent)
  - 🟡 **Medium**: Yellow (standard)
  - 🟢 **Low**: Green (lowest priority)
- **Interactive Popups**: Click on incident pins to see details
- **Status Information**: Shows current incident status and location

### 2. Deployment Tracking
- **Vehicle Markers**: Active deployments shown with vehicle-specific icons
- **Route Lines**: Visual representation of deployment routes
- **Real-time Updates**: Deployment status updates every 30 seconds
- **Driver Information**: Shows assigned driver and vehicle details

### 3. Route Optimization
- **Smart Routing**: Automatically calculates routes avoiding high-priority incidents
- **Incident Avoidance**: Routes are optimized to bypass critical areas
- **Multiple Options**: Choose between fastest route or incident-avoiding route
- **Real-time Warnings**: Alerts about incidents that may affect the route

## How to Use

### Viewing the Map
1. Navigate to **Live Map** from the main menu
2. The map automatically loads with current incidents and deployments
3. Use mouse to pan and zoom around the map
4. Click on markers to see detailed information

### Planning Routes
1. Click **"Plan Route"** button on the map
2. Enter start and end coordinates or use current location
3. Choose route optimization options:
   - ✅ Avoid high-priority incidents
   - ✅ Optimize for fastest route
4. Click **"Calculate Route"** to get optimized path
5. Click **"Show on Map"** to display the route

### Route Optimization Features
- **Automatic Detours**: Routes automatically avoid critical incident areas
- **Distance Calculation**: Shows total route distance in kilometers
- **Time Estimation**: Provides estimated travel time
- **Warning System**: Alerts about incidents that may cause delays

## Technical Implementation

### Map Controller
- **`getMapData()`**: Retrieves active incidents and deployments
- **`getRouteOptimization()`**: Calculates optimized routes avoiding incidents
- **Real-time Updates**: Data refreshes every 30 seconds

### Route Algorithm
- **Haversine Distance**: Calculates accurate distances between coordinates
- **Incident Proximity**: Identifies incidents within route buffer zones
- **Detour Calculation**: Generates alternative paths around critical areas
- **Multi-point Routing**: Supports complex routes with multiple waypoints

### Data Sources
- **Incidents**: From `incidents` table with priority and status
- **Deployments**: From `deployments` table with vehicle and driver info
- **Users**: Driver information from `users` table via `drivers` table

## Benefits

### For Emergency Responders
- **Better Situational Awareness**: See all active incidents at a glance
- **Optimized Response Routes**: Avoid traffic and incident areas
- **Real-time Updates**: Current information for better decision making

### For Dispatchers
- **Resource Visualization**: See where all vehicles and drivers are located
- **Route Planning**: Plan efficient deployment routes
- **Incident Management**: Monitor incident status and priority

### For System Administrators
- **Performance Monitoring**: Track response times and route efficiency
- **Data Analytics**: Analyze incident patterns and deployment effectiveness
- **System Optimization**: Identify areas for improvement

## Future Enhancements

### Planned Features
- **Traffic Integration**: Real-time traffic data for better routing
- **Weather Conditions**: Weather-aware route optimization
- **Mobile App**: Mobile version for field personnel
- **Historical Data**: Route performance analytics

### Advanced Routing
- **Machine Learning**: AI-powered route optimization
- **Predictive Analysis**: Anticipate incident locations
- **Multi-vehicle Coordination**: Optimize multiple deployment routes
- **Dynamic Re-routing**: Real-time route adjustments

## Troubleshooting

### Common Issues
1. **Map Not Loading**: Check internet connection and browser compatibility
2. **No Data Displayed**: Verify database connection and data availability
3. **Route Calculation Errors**: Ensure valid coordinates are entered
4. **Performance Issues**: Check browser console for JavaScript errors

### Browser Compatibility
- **Chrome**: Full support (recommended)
- **Firefox**: Full support
- **Safari**: Full support
- **Edge**: Full support
- **Internet Explorer**: Limited support (not recommended)

## API Endpoints

### Map Data
```
GET /index.php?action=map&method=getMapData
Response: JSON with incidents and deployments
```

### Route Optimization
```
GET /index.php?action=map&method=getRouteOptimization&start_lat=X&start_lng=Y&end_lat=Z&end_lng=W
Response: JSON with optimized route and warnings
```

## Security Considerations

### Data Protection
- **User Authentication**: Map access requires valid session
- **Data Validation**: All coordinates are validated before processing
- **SQL Injection Prevention**: Prepared statements for all database queries
- **XSS Protection**: Output encoding for user-generated content

### Access Control
- **Role-based Access**: Different map views for different user roles
- **Data Filtering**: Users only see incidents they have permission to view
- **Audit Logging**: Track map usage and route calculations

---

**Note**: This enhanced map system provides a comprehensive view of emergency response operations while ensuring efficient resource allocation and route optimization. 