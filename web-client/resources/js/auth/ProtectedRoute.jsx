import React, { useContext } from 'react';
import { Navigate } from 'react-router-dom';
import { AuthenticationProvider } from './AuthenticationProvider.jsx';

export default function ProtectedRoute({ children }) {
    const { user } = useContext(AuthenticationProvider);

    if (!user) return <Navigate to="/login" replace />;

    return children;
}
