import axios from 'axios';

export const fetchUsers = async () => {
    const result = await axios.get(`/api/fetch-users`, { withCredentials: true });
    return result.data;
}

export const fetchBingoGames = async () => {
    const result = await axios.get(`/api/fetch-bingo-games`, { withCredentials: true });
    return result.data;
}

export const fetchBingoEvents = async (gameid) => {
    const result = await axios.post(`/api/fetch-bingo-events`, { gameid }, { withCredentials: true });
    return result.data;
};

export const fetchBingoBoard = async (boardname, gameid) => {
    const result = await axios.post(`/api/fetch-bingo-board`, { boardname, gameid }, { withCredentials: true });
    return result.data;
}

export const updateBingoEvent = async (eventid, increase) => {
    return await axios.post(`/api/update-bingo-event`, { eventid, increase }, { withCredentials: true });
}

export const createBingoGame = async (name) => {
    return await axios.post(`/api/create-bingo-game`, { name }, { withCredentials: true });
}
