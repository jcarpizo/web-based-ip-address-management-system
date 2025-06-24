import React, { useState, useEffect } from 'react';
import api from '../api/axiosInstance.js';
import { formatDate } from '../utils/date';

export default function IpManagementLogs() {
    const [ipAddresses, setIpAddresses] = useState([]);
    const [userMap, setUserMap] = useState({});

    useEffect(() => {
        const fetchIpAddressesLogs = async () => {
            try {
                const { data } = await api.get(`/ip/logs`);
                const ips = data.ip_address_logs || [];

                const uniqueIds = Array.from(
                    new Set(ips.flatMap(({ added_by_user_id, updated_by_user_id }) => [added_by_user_id, updated_by_user_id]))
                );

                const map = {};
                await Promise.all(
                    uniqueIds.map(async (id) => {
                        try {
                            const res = await api.get(`/auth/user/${id}`);
                            map[id] = res.data.user.name;
                        } catch {
                            map[id] = `#${id}`;
                        }
                    })
                );

                setUserMap(map);
                setIpAddresses(ips);

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
                    <th>Old Values</th>
                    <th>New Values</th>
                    <th>Trigger Events</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Updated By</th>
                    <th>Updated Date</th>

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
                        <td>{userMap[ip.added_by_user_id] || ip.added_by_user_id}</td>
                        <td>{formatDate(ip.created_at)}</td>
                        <td>{userMap[ip.updated_by_user_id] || ip.updated_by_user_id}</td>
                        <td>{formatDate(ip.updated_at)}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
