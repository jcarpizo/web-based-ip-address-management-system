import React from 'react';
import {Routes, Route, Link} from 'react-router-dom';

import {AuthProvider} from "../auth/AuthenticationProvider.jsx";
import ProtectedRoute from "../auth/ProtectedRoute.jsx";

import LoginForm from "../components/ip-management-login.jsx";
import IpManagementDashboard from "../components/ip-management-dashboard.jsx";

export default function App() {
    return (
        <AuthProvider>
            <nav className="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
                <div className="container-fluid"><a className="navbar-brand" href="#">IP Management System</a>
                    <button className="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarCollapse"
                            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"><span
                        className="navbar-toggler-icon"></span></button>
                    <div className="collapse navbar-collapse" id="navbarCollapse">
                        <ul className="navbar-nav me-auto mb-2 mb-md-0">
                            <li className="nav-item"><a className="nav-link" href="#">IP Address Audit Logs</a></li>
                            <li className="nav-item"><a className="nav-link" href="#">User Login Audit Logs</a></li>
                            <li className="nav-item"><a className="nav-link" href="#">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <main style={{padding: '1rem'}}>
                <Routes>
                    <Route path="/login" element={<LoginForm/>}/>
                    <Route path="/dashboard" element={
                        <ProtectedRoute><IpManagementDashboard/></ProtectedRoute>
                    }/>
                </Routes>
            </main>
        </AuthProvider>
    );
}
