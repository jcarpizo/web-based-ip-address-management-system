import React, { createContext, useState, useEffect } from 'react';
import api from "../api/axiosInstance.js";

export const AuthenticationProvider = createContext({ user: null, setUser: () => {} });

export function AuthProvider({ children }) {
    const [user, setUser]     = useState(null);
    const [checking, setChecking] = useState(true);

    useEffect(() => {
        (async () => {
            try {

                const res = await api.post('/auth/verify');

                if (!res.data.error) {
                    localStorage.setItem('user', JSON.stringify(res.data));
                    setUser(res.data);
                    return;
                }

                if (res.data.message === 'token has already expired') {
                    const refreshRes = await api.post('/auth/refresh');
                    localStorage.setItem('access_token', refreshRes.data.access_token);

                    const newVerify = await api.post('/auth/verify');
                    if (!newVerify.data.error) {
                        localStorage.setItem('user', JSON.stringify(res.data));
                        setUser(newVerify.data);
                        return;
                    }
                }

                localStorage.removeItem('access_token');
                setUser(null);

            } catch (e) {
                localStorage.removeItem('access_token');
                setUser(null);

            } finally {
                setChecking(false);
            }
        })();
    }, []);

    if (checking) return <div>Checking authentication…</div>;
    return (
        <AuthenticationProvider.Provider value={{ user, setUser }}>
            {children}
        </AuthenticationProvider.Provider>
    );
}
