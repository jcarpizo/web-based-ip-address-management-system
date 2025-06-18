import React, { useState, useEffect, useCallback } from 'react';
import api from '../api/axiosInstance.js';
import { formatDate } from '../utils/date';
import { getCurrentUser } from '../utils/auth.js';

function useIpData() {
    const [ipAddresses, setIpAddresses] = useState([]);
    const [userMap, setUserMap] = useState({});

    const fetchIpData = useCallback(async () => {
        try {
            const { data } = await api.get('/ip/list');
            const ips = data.ip_address || [];
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

            setIpAddresses(ips);
            setUserMap(map);
        } catch {
            // Need to adjust some logic here, for nor nothing
        }
    }, []);

    useEffect(() => {
        fetchIpData();
    }, [fetchIpData]);

    return { ipAddresses, userMap, refetch: fetchIpData };
}

function IpModal({
                     show,
                     onClose,
                     onSubmit,
                     formData,
                     onChange,
                     errors,
                     isEditing,
                     alert,
                     clearAlert
                 }) {
    if (!show) return null;

    return (
        <div className="modal fade show d-block" tabIndex={-1}>
            <div className="modal-dialog">
                <div className="modal-content">
                    <form onSubmit={onSubmit}>
                        <div className="modal-header">
                            <h5 className="modal-title">{isEditing ? 'Edit IP Address' : 'New IP Address'}</h5>
                            <button type="button" className="btn-close" onClick={onClose} />
                        </div>
                        <div className="modal-body">
                            {alert.message && (
                                <div className={`alert alert-${alert.type} alert-dismissible fade show`}>
                                    {alert.message}
                                    <button
                                        type="button"
                                        className="btn-close"
                                        onClick={clearAlert}
                                    />
                                </div>
                            )}

                            {['label', 'ip_address'].map((field) => (
                                <div className="mb-3" key={field}>
                                    <label className="form-label">
                                        {field === 'ip_address' ? 'IP Address' : 'Label'}
                                    </label>
                                    <input
                                        name={field}
                                        type="text"
                                        className="form-control"
                                        value={formData[field]}
                                        onChange={onChange}
                                        required
                                    />
                                </div>
                            ))}

                            <div className="mb-3">
                                <label className="form-label">Comments</label>
                                <textarea
                                    name="comments"
                                    className="form-control"
                                    rows={4}
                                    value={formData.comments}
                                    onChange={onChange}
                                />
                            </div>
                            {errors.ip_address && <div className="text-danger">{errors.ip_address[0]}</div>}
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary" onClick={onClose}>
                                Cancel
                            </button>
                            <button type="submit" className="btn btn-primary">
                                {isEditing ? 'Update' : 'Create'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}

export default function IpManagementDashboard() {
    const { ipAddresses, userMap, refetch } = useIpData();

    const [form, setForm] = useState({
        label: '',
        ip_address: '',
        comments: '',
        added_by_user_id: '',
        updated_by_user_id: ''
    });
    const [editingId, setEditingId] = useState(null);
    const [showModal, setShowModal] = useState(false);
    const [alert, setAlert] = useState({ type: '', message: '' });
    const [errors, setErrors] = useState({});

    const currentUser = getCurrentUser();

    const openModal = (ip = null) => {
        if (ip) {
            setEditingId(ip.id);
            setForm({ ...ip, updated_by_user_id: currentUser.id });
        } else {
            setEditingId(null);
            setForm({
                label: '',
                ip_address: '',
                comments: '',
                added_by_user_id: currentUser.id,
                updated_by_user_id: currentUser.id
            });
            setErrors({});
        }
        setAlert({ type: '', message: '' });
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setEditingId(null);
        setErrors({});
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const showAlert = (type, message) => {
        setAlert({ type, message });
        setTimeout(() => setAlert({ type: '', message: '' }), 3000);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const endpoint = editingId
                ? `/ip/update/${editingId}`
                : '/ip/create';
            const method = editingId ? api.put : api.post;
            const response = await method(endpoint, form);
            const { success, errors: resErrors, message, ip_address: newIp } = response.data;

            if (!success || resErrors) {
                setErrors(resErrors || {});
                showAlert('danger', resErrors?.ip_address?.[0] || 'Validation failed');
                return;
            }

            if (editingId) {
                refetch();
            } else {
                refetch();
            }

            showAlert('success', message);
            closeModal();
        } catch (err) {
            const msg = err.response?.data?.message || 'An error occurred.';
            setErrors(err.response?.data?.errors || {});
            showAlert('danger', msg);
        }
    };

    const handleDelete = async (id) => {
        if (!window.confirm('Are you sure you want to delete this IP address?')) return;
        try {
            await api.delete(`/ip/delete/${id}`);
            showAlert('success', 'IP address deleted successfully.');
            refetch();
        } catch {
            showAlert('danger', 'Failed to delete IP address.');
        }
    };

    return (
        <div className="container mx-auto p-4">
            <br/><br/><br/>
            <button className="btn btn-success mb-3" onClick={() => openModal()}>
                + New IP Address
            </button>

            <table className="table small">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Label</th>
                    <th>IP Address</th>
                    <th>Comment</th>
                    <th>Created Date</th>
                    <th>Created By</th>
                    <th>Updated Date</th>
                    <th>Updated By</th>
                    <th />
                </tr>
                </thead>
                <tbody>
                {ipAddresses.map((ip, idx) => (
                    <tr key={ip.id}>
                        <td>{idx + 1}</td>
                        <td>{ip.label}</td>
                        <td>{ip.ip_address}</td>
                        <td>{ip.comments}</td>
                        <td>{formatDate(ip.created_at)}</td>
                        <td>{userMap[ip.added_by_user_id] || ip.added_by_user_id}</td>
                        <td>{formatDate(ip.updated_at)}</td>
                        <td>{userMap[ip.updated_by_user_id] || ip.updated_by_user_id}</td>
                        <td>
                            <div className="btn-group">
                                <button
                                    className="btn btn-sm btn-secondary"
                                    onClick={() => openModal(ip)}
                                >
                                    Edit
                                </button>
                                {currentUser.roles === 'admin' && (
                                    <button
                                        className="btn btn-sm btn-warning"
                                        onClick={() => handleDelete(ip.id)}
                                    >
                                        Delete
                                    </button>
                                )}
                            </div>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            <IpModal
                show={showModal}
                onClose={closeModal}
                onSubmit={handleSubmit}
                formData={form}
                onChange={handleChange}
                errors={errors}
                isEditing={Boolean(editingId)}
                alert={alert}
                clearAlert={() => setAlert({ type: '', message: '' })}
            />
        </div>
    );
}
