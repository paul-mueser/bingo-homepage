import axios from 'axios';

export const register = async (username, password, authCode) => {
    return axios.post(`/api/register`, { username, password, authCode });
};

export const login = async (username, password) => {
    return await axios.post(`/api/login`, { username, password });
};

export const verifyToken = async () => {
    return axios.get(`/api/verify-token`, {
        withCredentials: true
    });
};

export const refreshToken = async () => {
    return axios.get(`/api/refresh-token`, {
        withCredentials: true
    });
}

export const logout = async () => {
    return axios.get(`/api/logout`, {
        withCredentials: true
    });
}
