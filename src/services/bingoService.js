import axios from 'axios';

const API_URL = '{{API_URL}}';

export const fetchBingoEvents = async () => {
    const result = await axios.get(`${API_URL}/api/fetch-bingo-events`, { withCredentials: true });
    return result.data;
};

export const fetchBingoBoard = async (boardname) => {
    const result = await axios.post(`${API_URL}/api/fetch-bingo-board`, { boardname }, { withCredentials: true });
    return result.data;
}

export const updateBingoEvent = async (eventid, increase) => {
    return await axios.post(`${API_URL}/api/update-bingo-event`, { eventid, increase }, { withCredentials: true });
}
