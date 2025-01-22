import { defineStore } from "pinia";

export const useJobsStore = defineStore("jobs", {
    state: () => ({
        jobs: [],
    }),
    actions: {
        async fetchJobs() {
            try {
                const response = await fetch("/Data/data.json");
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                this.jobs = await response.json();
            } catch (error) {
                console.error(
                    "There has been a problem with your fetch operation:",
                    error
                );
            }
        },
    },
});
