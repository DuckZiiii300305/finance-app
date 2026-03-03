<template>
  <div class="card">
    <div class="card-header">
      <h3>Chi</h3>
      <button @click="showForm = !showForm">+ Thêm</button>
    </div>

    <TransactionForm
      v-if="showForm"
      type="expense"
      @created="fetchData"
    />

    <div v-for="item in expenses" :key="item.id" class="item expense">
      <span>{{ item.description }}</span>
      <span>{{ formatMoney(item.amount) }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import api from "@/services/api"
import TransactionForm from "./TransactionForm.vue"

const expenses = ref([])
const showForm = ref(false)

const fetchData = async () => {
  const res = await api.get("/expenses/today")
  expenses.value = res.data
}

onMounted(fetchData)

const formatMoney = (v) =>
  new Intl.NumberFormat("vi-VN").format(v) + " đ"
</script>

<style scoped>
.expense {
  color: #dc2626;
}
</style>