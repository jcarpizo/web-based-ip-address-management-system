import React, { useState } from 'react';
import api from "../api/axiosInstance.js";

export default function RegisterModal() {
    const [show, setShow] = useState(false);
    const [form, setForm] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
    });
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [loading, setLoading] = useState(false);

    const { password, password_confirmation } = form;
    const passwordsMismatch =
        password_confirmation.length > 0 && password !== password_confirmation;

    const handleChange = e => {
        setForm({ ...form, [e.target.name]: e.target.value });
        // clear general errors on any change
        setError('');
        setSuccess('');
    };

    const handleSubmit = async e => {
        e.preventDefault();
        // extra guard, though button is disabled when mismatch
        if (passwordsMismatch) {
            setError('Passwords do not match');
            return;
        }

        setError('');
        setSuccess('');
        setLoading(true);

        try {
            const { data } = await api.post('/auth/register', form);

            if (data.success === false) {
                if (data.errors) {
                    setError(Object.values(data.errors).flat().join(' '));
                } else if (data.message) {
                    setError(data.message);
                } else {
                    setError('Registration failed');
                }
            } else {
                setShow(false);
                setForm({ name: '', email: '', password: '', password_confirmation: '' });
                setSuccess('Successfully registered!');
            }
        } catch (err) {
            const d = err.response?.data;
            if (d?.errors) {
                setError(Object.values(d.errors).flat().join(' '));
            } else if (d?.message) {
                setError(d.message);
            } else {
                setError('Registration failed');
            }
        } finally {
            setLoading(false);
        }
    };

    return (
        <>
            <button className="btn btn-warning btn-sm" style={{ float: 'right', marginTop: '-30px' }}
                onClick={() => {
                    setShow(true);
                    setError('');
                    setSuccess('');
                }}>
                Register
            </button>

            <br/>

            {success && (
                <div className="alert alert-success text-center">
                    {success}
                </div>
            )}

            {show && (
                <div className="modal show fade d-block" tabIndex="-1" role="dialog">
                    <div className="modal-dialog" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">Register</h5>
                                <button
                                    type="button"
                                    className="btn-close"
                                    onClick={() => setShow(false)}
                                    disabled={loading}
                                />
                            </div>
                            <form onSubmit={handleSubmit}>
                                <div className="modal-body">
                                    {error && <div className="alert alert-danger">{error}</div>}

                                    <div className="mb-3">
                                        <label className="form-label">Name</label>
                                        <input
                                            name="name"
                                            type="text"
                                            className="form-control"
                                            value={form.name}
                                            onChange={handleChange}
                                            required
                                            disabled={loading}
                                        />
                                    </div>

                                    <div className="mb-3">
                                        <label className="form-label">Email</label>
                                        <input
                                            name="email"
                                            type="email"
                                            className="form-control"
                                            value={form.email}
                                            onChange={handleChange}
                                            required
                                            disabled={loading}
                                        />
                                    </div>

                                    <div className="mb-3">
                                        <label className="form-label">Password</label>
                                        <input
                                            name="password"
                                            type="password"
                                            className="form-control"
                                            value={form.password}
                                            onChange={handleChange}
                                            required
                                            disabled={loading}
                                        />
                                    </div>

                                    <div className="mb-3">
                                        <label className="form-label">Confirm Password</label>
                                        <input
                                            name="password_confirmation"
                                            type="password"
                                            className="form-control"
                                            value={form.password_confirmation}
                                            onChange={handleChange}
                                            required
                                            disabled={loading}
                                        />
                                        {passwordsMismatch && (
                                            <div className="form-text text-danger">
                                                Passwords do not match
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="modal-footer">
                                    <button
                                        type="button"
                                        className="btn btn-secondary"
                                        onClick={() => setShow(false)}
                                        disabled={loading}
                                    >
                                        Close
                                    </button>
                                    <button
                                        type="submit"
                                        className="btn btn-primary"
                                        disabled={loading || passwordsMismatch}
                                    >
                                        {loading ? 'Registering…' : 'Register'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
}
