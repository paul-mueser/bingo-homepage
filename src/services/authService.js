import axios from 'axios';

const API_URL = '{{API_URL}}';

export const register = async (username, password) => {
    return axios.post(`${API_URL}/api/register`, { username, password });
};

export const login = async (username, password) => {
    return await axios.post(`${API_URL}/api/login`, { username, password });
};

export const getProfile = async () => {
    return axios.get(`${API_URL}/#/`, {
        withCredentials: true
    });
};

export const verifyToken = async () => {
    return axios.get(`${API_URL}/api/verify-token`, {
        withCredentials: true
    });
};

export const refreshToken = async () => {
    return axios.get(`${API_URL}/api/refresh-token`, {
        withCredentials: true
    });
}

export const logout = async () => {
    return axios.get(`${API_URL}/api/logout`, {
        withCredentials: true
    });
}
