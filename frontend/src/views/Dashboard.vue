<template>
  <div class="min-h-screen bg-gray-100 p-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl font-semibold text-gray-800">
        Finance Dashboard
      </h1>

      <div class="bg-white px-6 py-3 rounded-2xl shadow">
        <div class="text-sm text-gray-500">Wallet Balance</div>
        <div
          class="text-xl font-bold"
          :class="dashboard?.balance >= 0 ? 'text-green-600' : 'text-red-600'"
        >
          {{ formatMoney(dashboard?.balance || 0) }}
        </div>
      </div>
    </div>

    <!-- 3 Column Layout -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- Income Section -->
      <div class="bg-white rounded-2xl shadow p-5">
        <div class="flex justify-between items-center mb-4">
          <h2 class="font-semibold text-green-600">Income</h2>
          <button
            @click="showIncomeForm = !showIncomeForm"
            class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1 rounded-lg"
          >
            + Add
          </button>
        </div>

        <!-- Inline Form -->
        <div v-if="showIncomeForm" class="mb-4 space-y-2">
          <input v-model="incomeForm.amount" type="number" placeholder="Amount"
            class="w-full border rounded-lg px-3 py-2 text-sm" />
          <input v-model="incomeForm.category" type="text" placeholder="Category"
            class="w-full border rounded-lg px-3 py-2 text-sm" />
          <button
            @click="addIncome"
            class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm"
          >
            Save
          </button>
        </div>

        <!-- Income List -->
        <div class="space-y-2 max-h-96 overflow-y-auto">
          <div
            v-for="t in incomes"
            :key="t.id + '-income'"
            class="flex justify-between items-center text-sm border-b pb-1"
            >
            <div>
                <div class="font-medium text-gray-700">{{ t.category }}</div>
                <div class="text-xs text-gray-400">{{ t.date }}</div>
            </div>

            <div class="flex items-center gap-2">
                <div class="text-green-600 font-semibold">
                + {{ formatMoney(t.amount) }}
                </div>

                <button
                @click="deleteIncome(t.id)"
                class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded"
                >
                X
                </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Expense Section -->
      <div class="bg-white rounded-2xl shadow p-5">
        <div class="flex justify-between items-center mb-4">
          <h2 class="font-semibold text-red-600">Expense</h2>
          <button
            @click="showExpenseForm = !showExpenseForm"
            class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded-lg"
          >
            + Add
          </button>
        </div>

        <!-- Inline Form -->
        <div v-if="showExpenseForm" class="mb-4 space-y-2">
          <input v-model="expenseForm.amount" type="number" placeholder="Amount"
            class="w-full border rounded-lg px-3 py-2 text-sm" />
          <input v-model="expenseForm.category" type="text" placeholder="Category"
            class="w-full border rounded-lg px-3 py-2 text-sm" />
          <button
            @click="addExpense"
            class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm"
          >
            Save
          </button>
        </div>

        <!-- Expense List -->
        <div class="space-y-2 max-h-96 overflow-y-auto">
          <div
            v-for="t in expenses"
            :key="t.id + '-expense'"
            class="flex justify-between items-center text-sm border-b pb-1"
            >
            <div>
                <div class="font-medium text-gray-700">{{ t.category }}</div>
                <div class="text-xs text-gray-400">{{ t.date }}</div>
            </div>

            <div class="flex items-center gap-2">
                <div class="text-red-600 font-semibold">
                - {{ formatMoney(t.amount) }}
                </div>

                <button
                @click="deleteExpense(t.id)"
                class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded"
                >
                X
                </button>
            </div>
          </div>
        </div>
      </div>
        <GoalSection />
      </div>

    </div>
  
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../services/api'
import GoalSection from '@/components/GoalSection.vue'

const dashboard = ref(null)

const showIncomeForm = ref(false)
const showExpenseForm = ref(false)
const showGoalForm = ref(false)

const incomeForm = ref({ amount: '', category: '' })
const expenseForm = ref({ amount: '', category: '' })

const loadDashboard = async () => {
  const res = await api.get('/dashboard')
  dashboard.value = res.data
}

onMounted(loadDashboard)

const incomes = computed(() =>
  dashboard.value?.transactions.filter(t => t.type === 'income') || []
)

const expenses = computed(() =>
  dashboard.value?.transactions.filter(t => t.type === 'expense') || []
)

const addIncome = async () => {
  await api.post('/incomes', {
    amount: incomeForm.value.amount,
    category: incomeForm.value.category,
    income_date: new Date().toISOString().split('T')[0]
  })
  incomeForm.value = { amount: '', category: '' }
  showIncomeForm.value = false
  loadDashboard()
}

const addExpense = async () => {
  await api.post('/expenses', {
    amount: expenseForm.value.amount,
    category: expenseForm.value.category,
    expense_date: new Date().toISOString().split('T')[0]
  })
  expenseForm.value = { amount: '', category: '' }
  showExpenseForm.value = false
  loadDashboard()
}
const deleteIncome = async (id) => {
  await api.delete(`/incomes/${id}`)
  loadDashboard()
}

const deleteExpense = async (id) => {
  await api.delete(`/expenses/${id}`)
  loadDashboard()
}

const deleteGoal = async (id) => {
  await api.delete(`/goals/${id}`)
  loadDashboard()
}
const formatMoney = (value) => {
  return Number(value).toLocaleString('vi-VN')
}
</script>