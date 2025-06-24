import React, { useState, useEffect } from 'react';
import api from '../api/axiosInstance.js';
import { formatDate } from '../utils/date';

export default function AuthManagementLogs() {
    const [ipAddresses, setIpAddresses] = useState([]);

    useEffect(() => {
        const fetchIpAddressesLogs = async () => {
            try {
                const { data } = await api.get(`/auth/logs`);
                setIpAddresses(data.user_logs || []);
            } catch (error) {
                console.error('Failed to load IP addresses Logs', error);
            }
        };
        fetchIpAddressesLogs();
    }, []);

    return (
        <div className="table-responsive">
            <table className="table small table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action Event</th>
                    <th>User Agent</th>
                    <th>Ip Address</th>
                    <th>Created Date</th>
                </tr>
                </thead>
                <tbody>
                {ipAddresses.map((ip, idx) => (
                    <tr key={ip.id}>
                        <td>{idx + 1}</td>
                        <td><span>{ip.user.name}</span></td>
                        <td><span>{ip.user.email}</span></td>
                        <td><span>{ip.user.roles}</span></td>
                        <td><span>{ip.action_event}</span></td>
                        <td><span>{ip.user_agent}</span></td>
                        <td><span>{ip.ip_address}</span></td>
                        <td>{formatDate(ip.created_at)}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
