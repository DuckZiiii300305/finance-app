<template>
  <div class="form">
    <input v-model="description" placeholder="Mô tả" />
    <input v-model.number="amount" type="number" placeholder="Số tiền" />
    <button @click="submit">Lưu</button>
  </div>
</template>

<script setup>
import { ref } from "vue"
import api from "@/services/api"

const props = defineProps(["type"])
const emit = defineEmits(["created"])

const description = ref("")
const amount = ref(0)

const submit = async () => {
  if (!amount.value) return

  if (props.type === "income") {
    await api.post("/incomes", { description: description.value, amount: amount.value })
  } else {
    await api.post("/expenses", { description: description.value, amount: amount.value })
  }

  description.value = ""
  amount.value = 0
  emit("created")
}
</script>