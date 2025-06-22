import axios from 'axios';

const api = axios.create({
    baseURL: 'http://127.0.0.1:8001/api',
    withCredentials: false,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-API-KEY': '3qohBUb4RJLUduNQ2ArCrokddmtmckl42vZ1g0IN'
    },
});

// Add access token to request if available
api.interceptors.request.use(config => {
    const token = localStorage.getItem('access_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Handle 401 errors without refresh logic
api.interceptors.response.use(
    response => response,
    error => {
        const { response } = error;

        if (response && response.status === 401) {
            localStorage.removeItem('access_token');
        }

        return Promise.reject(error);
    }
);

export default api;
