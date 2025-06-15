import {useContext, useState} from 'react'
import api from "../api/axiosInstance.js";
import {AuthenticationProvider} from "../auth/AuthenticationProvider.jsx";
import Cookies from 'js-cookie';

export default function LoginForm() {
    const [email, setEmail]       = useState('');
    const [password, setPassword] = useState('');
    const { setUser }             = useContext(AuthenticationProvider);

    const handleSubmit = async e => {
        e.preventDefault();
        try {
            const res = await api.post('/auth/login', { email, password });

            const token = res.data.access_token;
            Cookies.set('access_token', token, {
                expires: 1,        // days
                secure: true,      // send only over HTTPS
                sameSite: 'Strict',
            });

            localStorage.setItem('access_token', res.data.access_token);
            setUser(res.data.user);
            // hard redirect to dashboard (full page reload)
            window.location.href = '/dashboard';
        } catch (err) {
            alert(err.response?.data?.message || 'Login failed');
        }
    };


    return (
        <div className="container">

            <form className="form-signin" onSubmit={handleSubmit}>
                <div className="form-group">
                    <label htmlFor="exampleInputEmail1">Email address</label>
                    <input type="email" value={email} onChange={e => setEmail(e.target.value)} className="form-control"
                           placeholder="Email address" required/>
                    <small id="emailHelp" className="form-text text-muted">We'll never share your email with anyone
                        else.</small>
                </div>
                <div className="form-group">
                    <label htmlFor="exampleInputPassword1">Password</label>
                    <input type="password" value={password} onChange={e => setPassword(e.target.value)}
                           className="form-control" placeholder="Password" required/>
                </div>
                <hr></hr>
                <button className="btn btn-sm btn-primary btn-block" type="submit">Sign in</button>
            </form>

        </div>

    )
}
