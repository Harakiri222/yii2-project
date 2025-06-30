import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import ProductsView from '../views/ProductsView.vue'
import ProductDetail from '../views/ProductDetails.vue'
import ModerationView from '../views/ModerationView.vue'
import { useAuthStore } from '../stores/auth'
import { jwtDecode } from "jwt-decode";

const routes = [
  { path: '/login', component: Login },
  { path: '/products', component: ProductsView },
  { path: '/products/:id', component: ProductDetail },
  {
    path: '/moderation',
    component: ModerationView,
    beforeEnter: async (to, from, next) => {
      const authStore = useAuthStore()

      if (!authStore.token) return next('/login')

      if (!authStore.userRole) {
        try {
          await authStore.fetchUserRole()
        } catch (e) {
          return next('/login')
        }
      }

      if (authStore.userRole === 'moderator') {
        next()
      } else {
        next('/login')
      }
    }
  },
  { path: '/', redirect: '/products' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router