// src/routes/AdminRoute.jsx
import React from 'react';
import { Navigate } from 'react-router-dom';
import { getCurrentUser } from '../utils/auth';

export default function AdminRoute({ children }) {
    const user = getCurrentUser();

    if (!user || user.roles !== 'admin') {
        return <Navigate to="/dashboard" replace />;
    }

    return <>{children}</>;
}
