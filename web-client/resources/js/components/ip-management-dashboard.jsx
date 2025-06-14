import React, {useState, useEffect} from 'react';
import axios from 'axios';

export default function IpManagementDashboard() {
    const [ipAddress, setIpAddress] = useState([]);
    const [form, setForm] = useState({
        label: '',
        ip_address: '',
        comments: '',
        added_by_user_id: '',
        updated_by_user_id: '',
    });
    const [editingPost, setEditing] = useState(null);
    const [showModal, setShowModal] = useState(false);
    const [alert, setAlert] = useState({type: '', message: ''});
    const [errors, setErrors] = useState({});

    const fetchIpAddresses = () => {
        axios.get('http://127.0.0.1:8001/api/ip/list')
            .then(res => setIpAddress(res.data.ip_address))
            .catch(() => showAlert('danger', 'Failed to load data.'));
    };

    useEffect(fetchIpAddresses, []);

    const showAlert = (type, message) => {
        setAlert({type, message});
        setTimeout(() => setAlert({type: '', message: ''}), 3000);
    };

    const openNew = () => {
        setEditing(null);
        setForm({label: '', ip_address: '', comments: '', added_by_user_id: '', updated_by_user_id: ''});
        setShowModal(true);
        setErrors({});
    };

    const openEdit = ip => {
        setEditing(ip);
        setForm({
            label: ip.label,
            ip_address: ip.ip_address,
            comments: ip.comments,
            added_by_user_id: ip.added_by_user_id,
            updated_by_user_id: ip.updated_by_user_id
        });
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setEditing(null);
        setForm({label: '', ip_address: '', comments: '', added_by_user_id: '', updated_by_user_id: ''});
    };

    const handleSubmit = e => {
        e.preventDefault();
        const request = editingPost
            ? axios.put(`http://127.0.0.1:8001/api/ip/update/${editingPost.id}`, form)
            : axios.post('http://127.0.0.1:8001/api/ip/create', form);

        request
            .then(res => {

                if (res.data.success === false) {
                    setErrors(res.data.errors || {});
                    showAlert('danger', res.data.errors.ip_address[0]);
                    closeModal();
                    return;
                }

                if (editingPost) {
                    setIpAddress(ipAddress.map(p => (p.id === editingPost.id ? res.data.ip_address : p)));
                    showAlert('success', res.data.message);
                } else {
                    setIpAddress([...ipAddress, res.data]);
                    showAlert('success', res.data.message);
                    fetchIpAddresses();
                }
                closeModal();
            })
            .catch(err => {
                if (err.response?.data?.errors) {
                    setErrors(err.response.data.errors);
                } else {
                    const msg = err.response?.data?.message || 'An error occurred.';
                    showAlert('danger', msg);
                }
            });
    };

    const handleDelete = id => {

        if (!window.confirm("Are you sure you want to delete this IP address?")) {
            return;
        }

        axios.delete(`http://127.0.0.1:8001/api/ip/delete/${id}`)
            .then(() => {
                setIpAddress(ipAddress.filter(p => p.id !== id));
                showAlert('success', 'Ip Address deleted successfully.');
            })
            .catch(() => showAlert('danger', 'Failed to delete Ip Address.'));
    };

    return (
        <div className="container mx-auto p-4">
            <br/><br/>
            <br/><br/>

            {/* Alert */}
            {alert.message && (
                <div className={`alert alert-${alert.type} alert-dismissible fade show`}>
                    {alert.message}
                    <button
                        type="button"
                        className="btn-close"
                        onClick={() => setAlert({type: '', message: ''})}
                    />
                </div>
            )}

            <button className="btn btn-success mb-3" onClick={openNew}>
                + New IP Address
            </button>

            <table className="table small">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Label</th>
                    <th scope="col">IP Address</th>
                    <th scope="col">Comment</th>
                    <th scope="col">Created Date</th>
                    <th scope="col">Created By</th>
                    <th scope="col">Updated Date</th>
                    <th scope="col">Updated By</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                {ipAddress.map((ip, index) => (
                    <tr id={ip.id} key={ip.id}>
                        <td>{index + 1}</td>
                        <td>{ip.label}</td>
                        <td>{ip.ip_address}</td>
                        <td>{ip.comments}</td>
                        <td>{ip.created_at}</td>
                        <td>{ip.added_by_user_id}</td>
                        <td>{ip.updated_at}</td>
                        <td>{ip.added_by_user_id}</td>
                        <td>
                            <div className="btn-group" role="group">
                                <button className="btn btn-sm btn-secondary" onClick={() => openEdit(ip)}>Edit
                                </button>
                                <button className="btn btn-sm btn-warning"
                                        onClick={() => handleDelete(ip.id)}>Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            {/* Bootstrap Modal */}
            <div
                className={`modal fade${showModal ? ' show d-block' : ''}`}
                tabIndex="-1"
                aria-hidden={!showModal}
            >
                <div className="modal-dialog">
                    <div className="modal-content">
                        <form onSubmit={handleSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">
                                    {editingPost ? 'Edit IP Address' : 'New IP Address'}
                                </h5>
                                <button
                                    type="button"
                                    className="btn-close"
                                    onClick={closeModal}
                                    aria-label="Close"
                                />
                            </div>
                            <div className="modal-body">
                                <div className="mb-3">
                                    <label className="form-label">Label</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        value={form.label}
                                        onChange={e => setForm({...form, label: e.target.value})}
                                        required
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">IP Address</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        value={form.ip_address}
                                        onChange={e => setForm({...form, ip_address: e.target.value})}
                                        required
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">Comments</label>
                                    <textarea
                                        className="form-control"
                                        rows="4"
                                        value={form.comments}
                                        onChange={e => setForm({...form, comments: e.target.value})}
                                    />
                                    <input type="text" value={form.added_by_user_id}
                                           onChange={e => setForm({...form, added_by_user_id: e.target.value})}/>
                                    <input type="text" value={form.updated_by_user_id}
                                           onChange={e => setForm({...form, updated_by_user_id: e.target.value})}/>

                                </div>
                            </div>
                            <div className="modal-footer">
                                <button
                                    type="button"
                                    className="btn btn-secondary"
                                    onClick={closeModal}
                                >
                                    Cancel
                                </button>
                                <button type="submit" className="btn btn-primary">
                                    {editingPost ? 'Update' : 'Create'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}
