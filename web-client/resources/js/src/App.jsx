import React from 'react';
import {Routes, Route, Link} from 'react-router-dom';

import {AuthProvider} from "../auth/AuthenticationProvider.jsx";
import ProtectedRoute from "../auth/ProtectedRoute.jsx";

import LoginForm from "../components/ip-management-login.jsx";
import IpManagementDashboard from "../components/ip-management-dashboard.jsx";
import Navbar from "../components/ip-management-navbar.jsx";
import IpManagementLogs from "../components/ip-management-logs.jsx";

export default function App() {
    return (
        <AuthProvider>
            <Navbar />
            <main style={{padding: '1rem'}}>
                <Routes>
                    <Route path="/login" element={<LoginForm/>}/>
                    <Route path="/ip-audit-logs" element={<IpManagementLogs/>}/>
                    <Route path="/dashboard" element={
                        <ProtectedRoute><IpManagementDashboard/></ProtectedRoute>
                    }/>
                </Routes>
            </main>
        </AuthProvider>
    );
}
