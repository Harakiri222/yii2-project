<template>
  <div v-if="product" class="main-container">
    <div class="header">
      <div class="align">
        <span v-if="token" class="redirect" @click="auth.logout">Logout</span>
        <span v-if="!token" class="redirect" @click="redirectToLogin">Login</span>

        <span class="redirect" @click="redirectToMainPage">Main page</span>
        <span v-if="token && auth.userRole === 'moderator'" class="redirect" @click="redirectToModeration">Moderation</span>
      </div>
    </div>

    <div class="inner-container">
      <h1 class="text-xl font-bold">{{ product.name }}</h1>
      <p class="mb-4">{{ product.description }}</p>

      <form @submit.prevent="submitReview">
        <textarea v-model="text" class="border p-2 w-full" placeholder="Your feedback" />
        <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Remain a feedback</button>
      </form>

      <h2 class="mt-6 text-lg font-semibold">Feedbacks</h2>
      <ul>
        <li v-for="r in reviews" :key="r.id">{{ r.text }} <em>({{ r.status }})</em></li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { jwtDecode } from 'jwt-decode'

const route = useRoute()
const router = useRouter()
const product = ref(null)
const reviews = ref([])
const text = ref('')
const auth = useAuthStore()

const token = localStorage.getItem('token')
const decoded = token ? jwtDecode(token) : null

const redirectToLogin = () => {
  router.push('/login')
}

const redirectToMainPage = () => {
  router.push('/products')
}

async function submitReview() {
  if (!text.value) return

  const userId = decoded?.uid || null

  if (!userId) {
    alert('User not authenticated')
    return
  }

  await axios.post('/api/reviews', {
    product_id: product.value.id,
    user_id: userId,
    text: text.value
  }, {
    headers: { Authorization: `Bearer ${auth.token}` }
  })

  text.value = ''
  location.reload()
}

const redirectToModeration = () => {
  router.push('/moderation')
}

onMounted(async () => {
  const res = await axios.get(`/api/products/${route.params.id}`)
  product.value = res.data

  const rev = await axios.get('/api/reviews?expand=product,user')
  reviews.value = rev.data.filter(r => r.product_id === product.value.id)

  if (auth.token) {
    await auth.fetchUserRole()
  }

  console.log(decoded)
  console.log('Token:', token)
})

</script>

<style scoped>

.align {
  display: flex;
  align-items: center;
  gap: 20px;
}

.main-container {
  position: relative;
  width: 100%;
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
}

.header {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  width: 100%;
  gap: 20px;
}

.inner-container {
  width: 700px;
  display: flex;
  flex-direction: column;
  align-items: center;
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

div {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #222;
}

h1 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 10px;
  color: #b91c1c;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

p {
  font-size: 1.1rem;
  line-height: 1.5;
  margin-bottom: 25px;
  color: #444;
}

form {
  margin-bottom: 40px;
}

textarea {
  width: 100%;
  min-height: 90px;
  padding: 12px 15px;
  font-size: 1rem;
  border: 1.5px solid #fca5a5;
  border-radius: 8px;
  resize: vertical;
  font-family: inherit;
  color: #333;
  box-shadow: 0 1px 3px rgba(220, 38, 38, 0.1);
  transition: border-color 0.3s ease;
}

textarea:focus {
  outline: none;
  border-color: #dc2626;
  box-shadow: 0 0 8px rgba(220, 38, 38, 0.4);
}

button {
  margin-top: 12px;
  background-color: #dc2626;
  color: white;
  border: none;
  padding: 10px 22px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.25s ease;
}

button:hover {
  background-color: #b91c1c;
}

h2 {
  font-size: 1.4rem;
  font-weight: 600;
  margin-bottom: 15px;
  color: #b91c1c;
}

ul {
  width: 600px;
  list-style-type: none;
}

li {
  background-color: #fef3f3;
  border: 1px solid #fca5a5;
  border-radius: 8px;
  padding: 14px 20px;
  margin-bottom: 12px;
  box-shadow: 0 1px 4px rgba(220, 38, 38, 0.1);
  font-size: 1rem;
  color: #333;
}

li em {
  font-style: normal;
  color: #a855f7;
  margin-left: 10px;
  font-weight: 600;
}
</style>