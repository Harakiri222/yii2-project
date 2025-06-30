<template>
  <div class="main-container">
    <div class="header">
      <span v-if="token" class="redirect" @click="auth.logout">Logout</span>
      <span v-if="!token" class="redirect" @click="redirectToLogin">Login</span>
    </div>
    <div>
      <h1 class="text-red-500 text-3xl font-bold">Products list</h1>
      <div class="inner-container">
        <ul>
          <li v-for="product in products" :key="product.id">
            <router-link :to="`/products/${product.id}`">{{ product.name }}</router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const router = useRouter()

const products = ref([])
const token = localStorage.getItem('token')

const redirectToLogin = () => {
  router.push('/login')
}

onMounted(async () => {
  const res = await axios.get('/api/products')
  products.value = res.data

  console.log(localStorage.getItem('token'))
})

</script>

<style scoped>

.main-container {
  position: relative;
  width: 100%;
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
}

.inner-container {
  width: 700px;
}

.redirect {
  padding: 10px;
  cursor: pointer;
  font-weight: bold;
  font-size: 24px;
  color: #dc2626;
  transition: color 0.3s ease;
}

.redirect:hover {
  color: #b91c1c;
}

.header {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  width: 100%;
  gap: 20px;
}

div {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #222;
}

h1 {
  color: #dc2626;
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 30px;
  text-align: center;
  text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
}

ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  grid-template-columns: repeat(auto-fill,minmax(220px,1fr));
  gap: 20px;
}

li {
  background-color: #fef3f3;
  border: 1px solid #fca5a5;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(220, 38, 38, 0.15);
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}

li:hover {
  box-shadow: 0 6px 12px rgba(220, 38, 38, 0.3);
  transform: translateY(-5px);
}

a {
  display: block;
  padding: 18px 25px;
  color: #b91c1c;
  font-weight: 600;
  font-size: 1.1rem;
  text-decoration: none;
  border-radius: 10px;
  user-select: none;
}

a:hover {
  background-color: #dc2626;
  color: white;
  text-decoration: none;
}

a.router-link-exact-active {
  background-color: #b91c1c;
  color: white;
}
</style>