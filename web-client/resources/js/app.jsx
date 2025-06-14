import './bootstrap';

import React from 'react';
import ReactDOM from 'react-dom/client';
import IpManagementDashboard from "./components/ip-management-dashboard.jsx";

ReactDOM.createRoot(document.getElementById('ip-management-system-dashboard')).render(
    <React.StrictMode>
        <IpManagementDashboard />
    </React.StrictMode>
);
