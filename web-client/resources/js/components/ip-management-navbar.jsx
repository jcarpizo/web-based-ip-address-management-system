import React, { useContext } from 'react';
import {AuthenticationProvider} from "../auth/AuthenticationProvider.jsx";
import { useNavigate }   from 'react-router-dom';
import api from "../api/axiosInstance.js";

export default function Navbar() {
    const { user, setUser, checking } = useContext(AuthenticationProvider);
    const navigate = useNavigate();

    if (checking) return null;

    if (!user) {
        return null;
    }

    const handleLogout = async () => {
        try {
            await api.post('/auth/logout');
        } catch (err) {
            console.error('Logout request failed:', err);
        } finally {
            localStorage.removeItem('access_token');
            localStorage.removeItem('user');
            setUser(null);
            navigate('/login');
        }
    };

    return (
        <nav className="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div className="container-fluid">
                <a className="navbar-brand" href="#">IP Management System</a>
                <button className="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse" aria-controls="navbarCollapse"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarCollapse">
                    <ul className="navbar-nav me-auto mb-2 mb-md-0">
                        <li className="nav-item"><a className="nav-link" href="#">IP Address Audit Logs</a></li>
                        <li className="nav-item"><a className="nav-link" href="#">User Login Audit Logs</a></li>
                        <li className="nav-item">
                            <button className="nav-link btn btn-link" onClick={handleLogout}>
                                Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    );
}
