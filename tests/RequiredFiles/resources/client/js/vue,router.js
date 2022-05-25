import { createRouter, createWebHistory } from "vue-router";
import AppLayout from "../admin/App.vue";
/** IMPORTS */

/** ROUTES */
const routes = [
  {
    path: "/admin",
    name: "AdminAppLayout",
    component: AppLayout,
    children: []
  }
];
/** ROUTER */
export const router = createRouter({
  history: createWebHistory(),
  routes
});