# Emergency Facility System - Resource Allocation System

## Overview
The Emergency Facility System automatically determines the optimal emergency response locations and resources based on incident type and location. It intelligently routes emergency vehicles from the nearest appropriate facilities to incident locations.

## 🏥 **Emergency Facilities in Bago City**

### **Hospitals**
- **Bago City General Hospital** - Main emergency medical facility
- **Bago City Medical Center** - Secondary medical facility
- **Capabilities**: Emergency rooms, ICUs, operating rooms, ambulances

### **Police Stations**
- **Bago City Police Station** - Main law enforcement facility
- **Bago City Police Substation** - Community police facility
- **Capabilities**: Patrol cars, SWAT teams, investigation units

### **Fire Stations**
- **Bago City Fire Station** - Main fire and rescue facility
- **Bago City Fire Substation** - Secondary fire response facility
- **Capabilities**: Fire trucks, rescue equipment, hazmat units

### **Emergency Response Centers**
- **Bago City Emergency Response Center** - Coordination facility
- **Bago City Command Center** - Central command facility
- **Capabilities**: Emergency coordination, communication systems

## 🚨 **Automatic Resource Selection by Incident Type**

### **🔥 Fire Incidents**
**Automatically Deploys:**
- **Primary**: Fire trucks from nearest fire station
- **Secondary**: Ambulances from nearest hospital
- **Support**: Police units if needed for traffic control

**Example Scenario:**
```
Incident: Building fire at Main Street
Location: 10.5377, 122.8363

Automatic Deployment:
1. 🚒 Bago City Fire Station (0.5 km) - Fire trucks, rescue equipment
2. 🏥 Bago City General Hospital (0.3 km) - Ambulances, medical teams
3. 🚔 Bago City Police Station (0.8 km) - Traffic control, crowd management
```

### **🚑 Medical Emergencies**
**Automatically Deploys:**
- **Primary**: Ambulances from nearest hospital
- **Secondary**: Medical response teams
- **Support**: Emergency equipment and supplies

**Example Scenario:**
```
Incident: Heart attack at Central Park
Location: 10.5450, 122.8300

Automatic Deployment:
1. 🏥 Bago City Medical Center (0.2 km) - Ambulance, medical team
2. 🏥 Bago City General Hospital (0.8 km) - Backup ambulance if needed
```

### **🚔 Police Incidents**
**Automatically Deploys:**
- **Primary**: Police units from nearest station
- **Secondary**: Ambulances for medical support
- **Support**: Specialized response teams

**Example Scenario:**
```
Incident: Armed robbery at Shopping Mall
Location: 10.5400, 122.8400

Automatic Deployment:
1. 🚔 Bago City Police Station (0.1 km) - SWAT team, patrol cars
2. 🏥 Bago City General Hospital (0.5 km) - Ambulances, medical support
```

### **🚗 Traffic Accidents**
**Automatically Deploys:**
- **Primary**: Police for traffic control
- **Secondary**: Ambulances for medical care
- **Tertiary**: Fire trucks for rescue if needed

**Example Scenario:**
```
Incident: Multi-vehicle collision on Highway
Location: 10.5350, 122.8320

Automatic Deployment:
1. 🚔 Bago City Police Substation (0.1 km) - Traffic control, investigation
2. 🏥 Bago City General Hospital (0.6 km) - Ambulances, medical teams
3. 🚒 Bago City Fire Substation (0.4 km) - Rescue equipment, extrication tools
```

## 🧠 **Smart Deployment Algorithm**

### **1. Incident Analysis**
- **Type Classification**: Determines incident category (fire, medical, police, etc.)
- **Location Mapping**: Identifies incident coordinates
- **Priority Assessment**: Evaluates urgency and required response level

### **2. Facility Selection**
- **Proximity Calculation**: Uses Haversine formula for accurate distance calculation
- **Resource Matching**: Selects facilities based on incident type requirements
- **Capacity Assessment**: Considers facility capacity and available resources

### **3. Route Optimization**
- **Distance Calculation**: Computes shortest routes from facilities to incident
- **Time Estimation**: Estimates travel time including setup and response time
- **Priority Ranking**: Sorts deployment options by priority and efficiency

### **4. Multi-Response Coordination**
- **Simultaneous Deployment**: Creates multiple deployments for complex incidents
- **Resource Coordination**: Ensures complementary resources are deployed together
- **Status Tracking**: Monitors all deployments in real-time

## 📱 **User Interface Features**

### **Smart Deployment Creation**
- **Incident Selection**: Choose from reported incidents
- **Automatic Facility Detection**: System shows optimal facilities
- **Resource Assignment**: Select drivers and vehicles
- **One-Click Deployment**: Create multiple coordinated deployments

### **Real-Time Map Display**
- **Facility Markers**: Shows all emergency facilities with type-specific icons
- **Incident Pins**: Displays active incidents with priority colors
- **Deployment Routes**: Visualizes response routes and vehicle locations
- **Interactive Popups**: Click markers for detailed information

### **Deployment Management**
- **Status Updates**: Real-time deployment status tracking
- **Route Visualization**: Shows exact paths drivers should take
- **Resource Coordination**: Manages multiple deployments per incident
- **Performance Analytics**: Tracks response times and efficiency

## 🔧 **Technical Implementation**

### **Database Structure**
```sql
-- Facilities table
CREATE TABLE facilities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    facility_type ENUM('hospital', 'police_station', 'fire_station', 'emergency_center', 'command_center'),
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    contact_number VARCHAR(20),
    capacity INT DEFAULT 0,
    available_resources TEXT,
    is_active BOOLEAN DEFAULT TRUE
);
```

### **API Endpoints**
```
GET /index.php?action=facilities&method=getNearestFacilities
GET /index.php?action=facilities&method=getFacilitiesForIncident
GET /index.php?action=deployments&method=getOptimalDeployment
POST /index.php?action=deployments&method=createSmart
```

### **Distance Calculation**
```php
// Haversine formula for accurate distance calculation
private function haversineDistance($lat1, $lng1, $lat2, $lng2) {
    $earthRadius = 6371; // Earth's radius in kilometers
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng / 2) * sin($dLng / 2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    return $earthRadius * $c;
}
```

## 📊 **Priority Matrix System**

### **Incident Type Priority Mapping**
```php
$priorityMatrix = [
    'fire' => ['fire_station' => 10, 'hospital' => 8, 'police_station' => 5],
    'medical' => ['hospital' => 10, 'fire_station' => 6, 'police_station' => 4],
    'police' => ['police_station' => 10, 'hospital' => 7, 'fire_station' => 5],
    'traffic_accident' => ['police_station' => 9, 'hospital' => 8, 'fire_station' => 7],
    'natural_disaster' => ['fire_station' => 9, 'hospital' => 8, 'police_station' => 7],
    'chemical_spill' => ['fire_station' => 10, 'hospital' => 8, 'police_station' => 6],
    'bomb_threat' => ['police_station' => 10, 'fire_station' => 8, 'hospital' => 7],
    'hostage_situation' => ['police_station' => 10, 'hospital' => 7, 'fire_station' => 5],
    'gas_leak' => ['fire_station' => 10, 'hospital' => 8, 'police_station' => 6],
    'power_outage' => ['fire_station' => 10, 'hospital' => 6, 'police_station' => 5],
    'flooding' => ['fire_station' => 9, 'hospital' => 8, 'police_station' => 6],
    'earthquake' => ['fire_station' => 9, 'hospital' => 8, 'police_station' => 7]
];
```

## 🎯 **Benefits**

### **For Emergency Responders**
- **Faster Response Times**: Automatic facility selection reduces decision time
- **Optimal Resource Allocation**: Right resources deployed to right incidents
- **Better Coordination**: Multiple agencies respond simultaneously
- **Route Optimization**: Shortest paths calculated automatically

### **For Dispatchers**
- **Reduced Workload**: System handles complex routing decisions
- **Better Situational Awareness**: See all resources and their locations
- **Improved Efficiency**: Multiple deployments created with one click
- **Real-Time Updates**: Monitor all deployments simultaneously

### **For Citizens**
- **Faster Emergency Response**: Optimal resource allocation
- **Better Resource Utilization**: No wasted time or resources
- **Coordinated Response**: Multiple agencies work together seamlessly
- **Professional Emergency Management**: Modern, efficient system

## 🚀 **Future Enhancements**

### **Advanced Features**
- **Traffic Integration**: Real-time traffic data for route optimization
- **Weather Conditions**: Weather-aware deployment strategies
- **Resource Availability**: Real-time resource status tracking
- **Predictive Analytics**: Anticipate incident locations and patterns

### **Mobile Applications**
- **Field Personnel App**: Real-time updates for responders
- **Citizen App**: Emergency reporting and status tracking
- **Command Center App**: Mobile management interface
- **GPS Integration**: Real-time vehicle and personnel tracking

### **AI and Machine Learning**
- **Pattern Recognition**: Learn from historical incident data
- **Predictive Deployment**: Anticipate resource needs
- **Dynamic Routing**: Real-time route adjustments
- **Resource Optimization**: AI-powered resource allocation

---

## 🎉 **System Ready for Use**

The Emergency Facility System is now fully operational and provides:

✅ **Automatic facility selection** based on incident type  
✅ **Intelligent resource allocation** for optimal response  
✅ **Real-time route optimization** from facilities to incidents  
✅ **Multi-agency coordination** for complex emergencies  
✅ **Professional emergency management** interface  
✅ **Bago City facility integration** with accurate coordinates  

**Example Use Case**: When a fire is reported, the system automatically:
1. Identifies the incident location
2. Selects the nearest fire station and hospital
3. Calculates optimal routes
4. Creates coordinated deployments
5. Shows real-time tracking on the map

This system ensures that emergency responders arrive at incidents as quickly as possible with the right resources, saving lives and property in Bago City. 