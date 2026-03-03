<template>
  <div class="bg-white shadow rounded p-6 mb-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold">Goals</h2>

      <button
        @click="toggleForm"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        {{ showForm ? "Close" : "+ Add" }}
      </button>
    </div>

    <!-- Add Goal Form -->
    <GoalForm
      v-if="showForm"
      @goal-added="handleGoalAdded"
    />

    <div v-if="goals.length === 0" class="text-gray-500">
      No goals yet.
    </div>

    <!-- Goal List -->
    <div
      v-for="goal in goals"
      :key="goal.id"
      class="mb-6 border-b pb-4"
    >
      <div class="flex justify-between items-center mb-1">
        <div>
          <div class="font-semibold">{{ goal.name }}</div>
          <div class="text-sm text-gray-500">
            {{ goal.current_amount }} / {{ goal.target_amount }}
            • {{ goal.end_date }}
          </div>
        </div>

        <div class="flex gap-2">
          <button
            @click="toggleDeposit(goal.id)"
            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
          >
            + $
          </button>

          <button
            @click="confirmDelete(goal.id)"
            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
          >
            X
          </button>
        </div>
      </div>

      <!-- Progress -->
      <div class="w-full bg-gray-200 rounded h-3 mb-2">
        <div
          class="bg-green-500 h-3 rounded"
          :style="{ width: progress(goal) + '%' }"
        ></div>
      </div>

      <!-- Deposit Input -->
      <div v-if="depositingId === goal.id" class="flex gap-2">
        <input
          v-model.number="depositAmount"
          type="number"
          placeholder="Amount"
          class="border p-2 rounded w-40"
        />

        <button
          @click="deposit(goal.id)"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import api from "@/services/api"
import GoalForm from "./GoalForm.vue"

const goals = ref([])
const showForm = ref(false)

const depositingId = ref(null)
const depositAmount = ref(null)

function toggleForm() {
  showForm.value = !showForm.value
}

function toggleDeposit(id) {
  depositingId.value =
    depositingId.value === id ? null : id
  depositAmount.value = null
}

function progress(goal) {
  if (!goal.target_amount) return 0
  return Math.min(
    (goal.current_amount / goal.target_amount) * 100,
    100
  )
}

async function fetchGoals() {
  try {
    const res = await api.get("/goals")
    goals.value = res.data
  } catch (err) {
    console.error(err)
  }
}

function handleGoalAdded() {
  showForm.value = false
  fetchGoals()
}

async function deposit(id) {
  if (!depositAmount.value || depositAmount.value <= 0) return

  try {
    await api.post(`/goals/${id}/save`, {
      amount: depositAmount.value
    })

    depositingId.value = null
    depositAmount.value = null

    fetchGoals()
  } catch (err) {
    console.error("Deposit error:", err)
  }
}

async function confirmDelete(id) {
  const confirmed = window.confirm("Delete this goal?")
  if (!confirmed) return

  try {
    await api.delete(`/goals/${id}`)
    fetchGoals()
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchGoals)
</script>

<style scoped>
.card {
  background: white;
  border-radius: 18px;
  padding: 20px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.05);
}

.card-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 16px;
}

.btn-add {
  background: #2563eb;
  color: white;
  border: none;
  padding: 6px 14px;
  border-radius: 8px;
  cursor: pointer;
}

.goal-item {
  margin-bottom: 20px;
  padding-bottom: 14px;
  border-bottom: 1px solid #f1f1f1;
}

.goal-head {
  display: flex;
  justify-content: space-between;
  margin-bottom: 6px;
}

.goal-name {
  font-weight: 500;
}

.goal-percent {
  font-size: 14px;
  color: #6b7280;
}

.goal-amount {
  font-size: 13px;
  color: #6b7280;
  margin-bottom: 6px;
}

.progress {
  height: 8px;
  background: #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 10px;
}

.progress-bar {
  height: 100%;
  background: #2563eb;
}

.save-box {
  display: flex;
  gap: 8px;
}

.save-box input {
  flex: 1;
  padding: 6px;
  border-radius: 6px;
  border: 1px solid #ddd;
}

.save-box button {
  padding: 6px 12px;
  background: #16a34a;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
</style>