import React, { useState, useEffect, useCallback } from 'react';
import api from '../api/axiosInstance.js';

const initialForm = {
    label: '',
    ip_address: '',
    comments: '',
    added_by_user_id: '',
    updated_by_user_id: '',
};

export default function IpManagementDashboard() {
    const [ipAddresses, setIpAddresses] = useState([]);
    const [form, setForm] = useState(initialForm);
    const [editingId, setEditingId] = useState(null);
    const [showModal, setShowModal] = useState(false);
    const [alert, setAlert] = useState({ type: '', message: '' });
    const [errors, setErrors] = useState({});

    const showAlert = (type, message) => {
        setAlert({ type, message });
        setTimeout(() => setAlert({ type: '', message: '' }), 3000);
    };

    const fetchIpAddresses = useCallback(async () => {
        try {
            const { data } = await api.get('/ip/list');
            setIpAddresses(data.ip_address || []);
        } catch {
            showAlert('danger', 'Failed to load data.');
        }
    }, []);

    useEffect(() => {
        fetchIpAddresses();
    }, [fetchIpAddresses]);

    const openModal = (ip = null) => {

        const stored = localStorage.getItem('user');
        const userId = stored ? JSON.parse(stored).id : '';

        if (ip) {
            setEditingId(ip.id);
            setForm({
                label: ip.label,
                ip_address: ip.ip_address,
                comments: ip.comments,
                added_by_user_id: userId,
                updated_by_user_id: userId,
            });
        } else {
            setEditingId(null);
            setForm({
                ...initialForm,
                added_by_user_id: userId,         // set both to current user
                updated_by_user_id: userId,
            });
            setErrors({});
        }
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setEditingId(null);
        setForm(initialForm);
        setErrors({});
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = editingId
                ? await api.put(`/ip/update/${editingId}`, form)
                : await api.post('/ip/create', form);

            const { success, errors: resErrors, message, ip_address: newIp } = response.data;

            if (success === false || resErrors) {
                setErrors(resErrors || {});
                showAlert('danger', resErrors?.ip_address?.[0] || 'Validation failed');
                return;
            }

            if (editingId) {
                setIpAddresses((prev) => prev.map((ip) => (ip.id === editingId ? newIp : ip)));
            } else {
                await fetchIpAddresses();
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
            setIpAddresses((prev) => prev.filter((ip) => ip.id !== id));
            showAlert('success', 'IP address deleted successfully.');
        } catch {
            showAlert('danger', 'Failed to delete IP address.');
        }
    };

    return (
        <div className="container mx-auto p-4">
            <br/><br/><br/><br/>
            {/* Alert */}
            {alert.message && (
                <div className={`alert alert-${alert.type} alert-dismissible fade show`}>
                    {alert.message}
                    <button type="button" className="btn-close" onClick={() => setAlert({ type: '', message: '' })} />
                </div>
            )}

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
                        <td>{ip.created_at}</td>
                        <td>{ip.added_by_user_id}</td>
                        <td>{ip.updated_at}</td>
                        <td>{ip.updated_by_user_id}</td>
                        <td>
                            <div className="btn-group">
                                <button className="btn btn-sm btn-secondary" onClick={() => openModal(ip)}>
                                    Edit
                                </button>
                                <button className="btn btn-sm btn-warning" onClick={() => handleDelete(ip.id)}>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            {/* Modal */}
            {showModal && (
                <div className="modal fade show d-block" tabIndex="-1">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <form onSubmit={handleSubmit}>
                                <div className="modal-header">
                                    <h5 className="modal-title">
                                        {editingId ? 'Edit IP Address' : 'New IP Address'}
                                    </h5>
                                    <button type="button" className="btn-close" onClick={closeModal} />
                                </div>
                                <div className="modal-body">
                                    <div className="mb-3">
                                        <label className="form-label">Label</label>
                                        <input
                                            name="label"
                                            type="text"
                                            className="form-control"
                                            value={form.label}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                    <div className="mb-3">
                                        <label className="form-label">IP Address</label>
                                        <input
                                            name="ip_address"
                                            type="text"
                                            className="form-control"
                                            value={form.ip_address}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                    <div className="mb-3">
                                        <label className="form-label">Comments</label>
                                        <textarea
                                            name="comments"
                                            className="form-control"
                                            rows="4"
                                            value={form.comments}
                                            onChange={handleChange}
                                        />
                                    </div>
                                    {errors.ip_address && <div className="text-danger">{errors.ip_address[0]}</div>}
                                </div>
                                <div className="modal-footer">

                                    <button type="button" className="btn btn-secondary" onClick={closeModal}>
                                        Cancel
                                    </button>
                                    <button type="submit" className="btn btn-primary">
                                        {editingId ? 'Update' : 'Create'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
