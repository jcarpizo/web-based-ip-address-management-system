import React, { useState, useEffect } from 'react';
import api from '../api/axiosInstance.js';
import { formatDate } from '../utils/date';

export default function IpManagementLogs() {
    const [ipAddresses, setIpAddresses] = useState([]);

    useEffect(() => {
        const fetchIpAddressesLogs = async () => {
            try {
                const { data } = await api.get(`/ip/logs`);
                setIpAddresses(data.ip_address_logs || []);
            } catch (error) {
                console.error('Failed to load IP addresses Logs', error);
            }
        };
        fetchIpAddressesLogs();
    }, []);

    return (
        <div className="table-responsive">
            <br/><br/> <br/>
            <table className="table small table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                    <th>Trigger Events</th>
                    <th>Updated Date</th>
                    <th>Updated By</th>
                </tr>
                </thead>
                <tbody>
                {ipAddresses.map((ip, idx) => (
                    <tr key={ip.id}>
                        <td>{idx + 1}</td>
                        <td>
                            <pre style={{ background: '#ffebc0', padding: '1em', borderRadius: 4 , width: '500px'}}>
                                 {ip.old_value ?? 'no old values' }
                            </pre>
                        </td>
                        <td>
                             <pre style={{ background: '#bfffaa', padding: '1em', borderRadius: 4 , width: '500px'}}>
                                {ip.new_value ?? 'no new values'}
                            </pre>
                        </td>
                        <td><span>{ip.event}</span></td>
                        <td>{formatDate(ip.created_at)}</td>
                        <td>{formatDate(ip.updated_at)}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
