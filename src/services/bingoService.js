import axios from 'axios';

const API_URL = '{{API_URL}}';

export const fetchUsers = async () => {
    const result = await axios.get(`${API_URL}/api/fetch-users`, { withCredentials: true });
    return result.data;
}

export const fetchBingoGames = async () => {
    const result = await axios.get(`${API_URL}/api/fetch-bingo-games`, { withCredentials: true });
    return result.data;
}

export const fetchBingoEvents = async (gameid) => {
    const result = await axios.post(`${API_URL}/api/fetch-bingo-events`, { gameid }, { withCredentials: true });
    return result.data;
};

export const fetchBingoBoard = async (boardname, gameid) => {
    const result = await axios.post(`${API_URL}/api/fetch-bingo-board`, { boardname, gameid }, { withCredentials: true });
    return result.data;
}

export const updateBingoEvent = async (eventid, increase) => {
    return await axios.post(`${API_URL}/api/update-bingo-event`, { eventid, increase }, { withCredentials: true });
}
