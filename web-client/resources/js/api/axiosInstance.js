import axios from 'axios';

const api = axios.create({
    baseURL: 'http://127.0.0.1:8001/api',
    withCredentials: false, // send cookies (for refresh token)
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    rewrite: path => path.replace(/^\/api/, ''),
});

api.interceptors.request.use(config => {
    const token = localStorage.getItem('access_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

let isRefreshing = false;
let failedQueue = [];

const processQueue = (error, token = null) => {
    failedQueue.forEach(prom => {
        if (error) {
            prom.reject(error);
        } else {
            prom.resolve(token);
        }
    });
    failedQueue = [];
};

api.interceptors.response.use(
    response => response,
    err => {
        const { config, response } = err;
        const originalRequest = config;

        if (response && response.status === 401 && !originalRequest._retry) {
            if (isRefreshing) {
                return new Promise(function(resolve, reject) {
                    failedQueue.push({ resolve, reject });
                })
                    .then(token => {
                        originalRequest.headers.Authorization = `Bearer ${token}`;
                        return api(originalRequest);
                    })
                    .catch(err => Promise.reject(err));
            }

            originalRequest._retry = true;
            isRefreshing = true;

            return new Promise((resolve, reject) => {
                api.post('/auth/refresh')
                    .then(({ data }) => {
                        const newAccessToken = data.access_token;
                        localStorage.setItem('access_token', newAccessToken);
                        api.defaults.headers.common.Authorization = `Bearer ${newAccessToken}`;
                        processQueue(null, newAccessToken);
                        resolve(api(originalRequest));
                    })
                    .catch(err => {
                        processQueue(err, null);
                        // if refresh failed, clear auth
                        localStorage.removeItem('access_token');
                        reject(err);
                    })
                    .finally(() => {
                        isRefreshing = false;
                    });
            });
        }

        return Promise.reject(err);
    }
);

export default api;
