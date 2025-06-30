<template>
  <div class="main-container">
    <div class="header">
      <div class="redirect" @click="redirectToMainPage">Main page</div>
    </div>
    <div class="inner-container">
      <h1>Feedback Moderation</h1>

      <div v-if="reviews.length === 0" class="empty-message">
        There are no feedbacks to moderate.
      </div>

      <ul>
        <li v-for="review in reviews" :key="review.id">
          <p><strong>Product:</strong> <em>{{ review.product_name }}</em></p>
          <p><strong>Author:</strong> <em>{{ review.user_name }}</em></p>
          <p class="review-text">{{ review.text }}</p>
          <button @click="approveReview(review.id)">Confirm</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const router = useRouter()
const reviews = ref([])
const auth = useAuthStore()

async function fetchPendingReviews() {
  try {
    const res = await axios.get('/api/reviews?expand=product,user', {
      headers: {
        Authorization: `Bearer ${auth.token}`
      }
    })
    reviews.value = res.data.filter(r => r.status === 'pending')
  } catch (e) {
    console.error('Error while loading products', e)
  }
}

async function approveReview(id) {
  try {
    await axios.put(`/api/reviews/${id}/approve`, {}, {
      headers: {
        Authorization: `Bearer ${auth.token}`
      }
    })
    reviews.value = reviews.value.filter(r => r.id !== id)
  } catch (e) {
    alert('Confirmation error')
  }
}

const redirectToMainPage = () => {
  router.push('/products')
}

onMounted(() => {
  fetchPendingReviews()
  console.log(reviews.value)
})

</script>

<style scoped>
.main-container {
  position: relative;
  width: 100%;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
}

.inner-container {
  width: 700px;
  display: flex;
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

h1 {
  font-size: 2rem;
  font-weight: bold;
  color: #dc2626;
  margin-bottom: 24px;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
}

.empty-message {
  font-size: 1.2rem;
  color: #777;
  margin-top: 20px;
}

ul {
  width: 100%;
  list-style: none;
  padding: 0;
}

li {
  background-color: #fef3f3;
  border: 1.5px solid #fca5a5;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 16px;
  box-shadow: 0 2px 6px rgba(220, 38, 38, 0.08);
  transition: transform 0.2s ease;
}

li:hover {
  transform: translateY(-2px);
}

.review-text {
  margin: 16px 0;
  font-size: 1.05rem;
  color: #444;
}

em {
  font-style: normal;
  color: #a855f7;
  font-weight: 600;
  margin-left: 5px;
}

button {
  background-color: #dc2626;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.25s ease;
}

button:hover {
  background-color: #b91c1c;
}
</style>