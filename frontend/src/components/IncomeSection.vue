<template>
  <div class="card">
    <div class="card-header">
      <h3>Thu</h3>
      <button @click="showForm = !showForm">+ Thêm</button>
    </div>

    <TransactionForm
      v-if="showForm"
      type="income"
      @created="fetchData"
    />

    <div v-for="item in incomes" :key="item.id" class="item income">
      <span>{{ item.description }}</span>
      <span>{{ formatMoney(item.amount) }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import api from "@/services/api"
import TransactionForm from "./TransactionForm.vue"

const incomes = ref([])
const showForm = ref(false)

const fetchData = async () => {
  const res = await api.get("/incomes/today")
  incomes.value = res.data
}

onMounted(fetchData)

const formatMoney = (v) =>
  new Intl.NumberFormat("vi-VN").format(v) + " đ"
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

.item {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #f1f1f1;
}

.income {
  color: #16a34a;
}
</style>