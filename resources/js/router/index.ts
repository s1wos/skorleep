import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import Home from '@/pages/Home.vue'
import AppLayout from '@/layout/AppLayout.vue'
import About from '@/pages/About.vue'
import Help from '@/pages/Help.vue'

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
      {
        path: 'about',
        name: 'About',
        component: About,
      },
      {
        path: 'help',
        name: 'Help',
        component: Help,
      },
    ]
  }
]

export const router = createRouter({
    history: createWebHistory(),
    routes
})