import { reactive } from "vue"

const API = "http://localhost:8000/api"

export const financeStore = reactive({
  date: new Date().toISOString().slice(0, 10),
  balance: 0,
  income_total: 0,
  expense_total: 0,
  incomes: [],
  expenses: [],
  goals: [],

  async loadDashboard() {
    const res = await fetch(`${API}/dashboard?date=${this.date}`)
    const data = await res.json()

    this.balance = data.balance
    this.income_total = data.income_total
    this.expense_total = data.expense_total
    this.incomes = data.incomes
    this.expenses = data.expenses
    this.goals = data.goals
  },

  async createIncome(payload) {
    await fetch(`${API}/incomes`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    })
    await this.loadDashboard()
  },

  async updateIncome(id, payload) {
    await fetch(`${API}/incomes/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    })
    await this.loadDashboard()
  },

  async deleteIncome(id) {
    await fetch(`${API}/incomes/${id}`, { method: "DELETE" })
    await this.loadDashboard()
  },

  async createExpense(payload) {
    await fetch(`${API}/expenses`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    })
    await this.loadDashboard()
  },

  async updateExpense(id, payload) {
    await fetch(`${API}/expenses/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    })
    await this.loadDashboard()
  },

  async deleteExpense(id) {
    await fetch(`${API}/expenses/${id}`, { method: "DELETE" })
    await this.loadDashboard()
  },

  async createGoal(payload) {
    await fetch(`${API}/goals`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    })
    await this.loadDashboard()
  },

  async addMoneyToGoal(id, amount) {
    await fetch(`${API}/goals/${id}/add`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ amount })
    })
    await this.loadDashboard()
  },

  async deleteGoal(id) {
    await fetch(`${API}/goals/${id}`, { method: "DELETE" })
    await this.loadDashboard()
  }
})