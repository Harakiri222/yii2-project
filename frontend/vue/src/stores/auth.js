import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
import { jwtDecode } from 'jwt-decode'

export const useAuthStore = defineStore('auth', () => {
    const token = ref(localStorage.getItem('token') || null)
    const userRole = ref(null)

    const login = async (username, password) => {
        const response = await axios.post('/api/login', { username, password })
        token.value = response.data.token
        localStorage.setItem('token', token.value)
        await fetchUserRole()
    }

    const logout = () => {
        token.value = null
        userRole.value = null
        localStorage.removeItem('token')
        location.reload()
    }

    const fetchUserRole = async () => {
        if (!token.value) return
        const decoded = jwtDecode(token.value)
        const res = await axios.get(`/api/user-role/${decoded.uid}`, {
            headers: { Authorization: `Bearer ${token.value}` }
        })
        userRole.value = res.data.role
    }

    return {
        token,
        userRole,
        login,
        logout,
        fetchUserRole,
    }
})