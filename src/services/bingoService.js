import axios from 'axios';

const API_URL = '{{API_URL}}';

export const fetchBingoEvents = async () => {
    const result = await axios.post(`${API_URL}/api/fetch-bingo-events`);
    console.log(result);
    return result;
};

export const fetchBingoBoard = async (boardname) => {
    const result = "test + " + boardname;
    //const result = await axios.post(`${API_URL}/api/fetch-bingo-board`, { boardname });
    console.log(result);
    return result;
}
