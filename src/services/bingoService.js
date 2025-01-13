import axios from 'axios';

const API_URL = '{{API_URL}}';

export const fetchBingoEvents = async () => {
    const result = await axios.post(`${API_URL}/api/fetch-bingo-events`);
    return result.data;
};

export const fetchBingoBoard = async (boardname) => {
    const result = await axios.post(`${API_URL}/api/fetch-bingo-board`, { boardname });
    console.log(result);
    return result;
}

export const updateBingoEvent = async (eventid, increase) => {
    return await axios.post(`${API_URL}/api/update-bingo-event`, { eventid, increase });
}
