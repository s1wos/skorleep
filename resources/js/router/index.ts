import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import Home from '@/pages/Home.vue'
import AppLayout from '@/layout/AppLayout.vue'

const routes: RouteRecordRaw[] = [
    {
    path: '/',
    component: AppLayout,
    children: [
      {
        path: '',
        name: 'Home',
        component: Home,
      },
    ]
  }
]

export const router = createRouter({
    history: createWebHistory(),
    routes
})