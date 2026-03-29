import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
    state: () => ({
        username: "",
        isAdmin: false,
        id: -1,
    }),
    persist: true,

    actions: {
        setUser(userData) {
            if (userData) {
                this.username = userData.username;
                this.isAdmin = userData.isAdmin;
                this.id = userData.id;
            } else {
                this.clearUser();
            }
        },

        clearUser() {
            this.username = "";
            this.isAdmin = false;
            this.id = -1;
        },
    }
});