import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import JobListings from '../views/JobListings.vue'
import Login from '../views/Login.vue'
import Registration from '../views/Registration.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/jobs",
      name: "jobs",
      component: JobListings,
    },
    {
      path: "/login",
      name: "login",
      component: Login,
    },
    {
      path: "/registration",
      name: "registration",
      component: Registration,
    },
    {
      path: "/job/:id",
      name: "job-details",
      component: () => import("../views/JobDetails.vue"),
    },
    {
      path: "/registration",
      name: "Registration",
      component: () => import("../views/Registration.vue"),
    },
    {
      path: "/FindJob",
      name: "FindJob",
      component: () => import("../views/FindJob.vue"),
    },
    {
      path: "/profile",
      name: "profile",
      component: () => import("../views/Profile.vue"),
      meta: { requiresAuth: true }
    }
  ],
});

export default router
