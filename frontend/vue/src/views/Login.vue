<template>
  <div class="main-container">
    <div class="header">
      <div class="align">
        <span v-if="token" class="redirect" @click="auth.logout">Logout</span>
        <div class="redirect" @click="redirectToMainPage">Main page</div>
      </div>
    </div>
    <div class="login-container">
      <form class="login-form" @submit.prevent="submit">
        <h2>Login</h2>
        <input v-model="username" placeholder="Login" class="input" />
        <input v-model="password" type="password" placeholder="Password" class="input" />
        <button class="login-button">Login</button>
        <p v-if="error" class="error-message">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const username = ref('')
const password = ref('')
const error = ref(null)
const auth = useAuthStore()
const router = useRouter()
const token = localStorage.getItem('token')

async function submit() {
  error.value = null
  try {
    await auth.login(username.value, password.value)
    await router.push('/products')
  } catch {
    error.value = 'Wrong login or password'
  }
}

const redirectToMainPage = () => {
  router.push('/products')
}

onMounted(() => {
  if (auth.token) {
    auth.fetchUserRole()
  }
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
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

.login-container {
  width: 400px;
  margin: 80px auto;
  padding: 30px;
  background: #f7f9fc;
  border: 1px solid #e0e6ed;
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.login-form h2 {
  margin-bottom: 10px;
  font-size: 24px;
  text-align: center;
  color: #2c3e50;
}

.input {
  padding: 10px 14px;
  border: 1px solid #ccd6dd;
  border-radius: 6px;
  font-size: 16px;
  transition: border-color 0.2s;
}

.input:focus {
  outline: none;
  border-color: #409eff;
}

.login-button {
  background-color: #409eff;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.login-button:hover {
  background-color: #2d8cf0;
}

.error-message {
  color: #e74c3c;
  font-size: 14px;
  text-align: center;
}
</style>