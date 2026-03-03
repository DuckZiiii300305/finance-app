<template>
  <div class="border rounded p-4 mb-4 bg-gray-50">
    <form @submit.prevent="submitGoal" class="space-y-3">
      <input
        v-model="form.name"
        type="text"
        placeholder="Goal name"
        class="border p-2 w-full rounded"
        required
      />

      <input
        v-model.number="form.target_amount"
        type="number"
        placeholder="Target amount"
        class="border p-2 w-full rounded"
        required
      />

      <input
        v-model="form.end_date"
        type="date"
        class="border p-2 w-full rounded"
        required
      />

      <button
        type="submit"
        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
      >
        Save
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from "vue"
import api from "@/services/api"

const emit = defineEmits(["goal-added"])

const form = reactive({
  title: "",
  target_amount: "",
  end_date: ""
})

async function submitGoal() {
  try {
    await api.post("/goals", form)

    emit("goal-added")

    form.name = ""
    form.target_amount = ""
    form.end_date = ""
  } catch (err) {
    console.error("Add goal error:", err)
  }
}
</script>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 16px;
}

.form input {
  padding: 8px;
  border-radius: 8px;
  border: 1px solid #ddd;
}

.form button {
  padding: 8px;
  border-radius: 8px;
  background: #2563eb;
  color: white;
  border: none;
  cursor: pointer;
}
</style>