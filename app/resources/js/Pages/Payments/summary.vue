<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
    'summary_ym' : String,
    'members' : Array,
    'categories' : Array,
    'paymentsIncome' : Array,
    'paymentsExpense' : Array
})

onMounted(() => {
    props.members.forEach(member => {
        memberList.value.push({
            member_id: member.member_id,
            member_name: member.member_name
        })
        memberIndexArray.value.push(member.member_id)
    })
    props.categories.forEach(category => {
        if(category.income_flg === 1) {
            categoryIncomeList.value.push({
                category_id: category.category_id,
                category_name: category.category_name,
                income_flg: category.income_flg
            })
            categoryIncomeIndexArray.value.push(category.category_id)
        }
        else {
            categoryExpneseList.value.push({
                category_id: category.category_id,
                category_name: category.category_name,
                income_flg: category.income_flg
            })
            categoryExpenseIndexArray.value.push(category.category_id)
        }
    })
    props.paymentsIncome.forEach(income => {
        incomeList.value.push({
            member_id: income.member_id,
            category_id: income.category_id,
            amount: income.amount
        })
    })
    props.paymentsExpense.forEach(expense => {
        expenseList.value.push({
            member_id: expense.member_id,
            category_id: expense.category_id,
            amount: expense.amount
        })
    })

    paymentsIncomeArray = new Array(categoryIncomeIndexArray.value.length)
    for(let i=0; i<paymentsIncomeArray.length; i++) {
        paymentsIncomeArray[i] = new Array(memberIndexArray.value.length).fill("")
    }
    incomeList.value.forEach(income => {
        paymentsIncomeArray[categoryIncomeIndexArray.value.indexOf(income.category_id)][memberIndexArray.value.indexOf(income.member_id)] = income.amount;
    })

    paymentsExpenseArray = new Array(categoryExpenseIndexArray.value.length)
    for(let i=0; i<paymentsExpenseArray.length; i++) {
        paymentsExpenseArray[i] = new Array(memberIndexArray.value.length).fill("")
    }
    expenseList.value.forEach(expence => {
        paymentsExpenseArray[categoryExpenseIndexArray.value.indexOf(expence.category_id)][memberIndexArray.value.indexOf(expence.member_id)] = expence.amount;
    })
})
const memberList = ref([])
const memberIndexArray = ref([])
const categoryIncomeList = ref([])
const categoryIncomeIndexArray = ref([])
const categoryExpneseList = ref([])
const categoryExpenseIndexArray = ref([])
const incomeList = ref([])
const expenseList = ref([])
let paymentsIncomeArray = ref()
let paymentsExpenseArray = ref()

const commaSeparateOrBlank = args => {
    if (args !== "") {
        return Number(args).toLocaleString()
    }
    else {
        return "";
    }
}
</script>

<template>
    <Head title="収支詳細" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                収支詳細
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-900 body-font">
                            <div class="container px-5 py-24 mx-auto" style="min-height:500px;">
                                <h1 class="text-3xl">{{ props.summary_ym.substring(0, 4) + '年' + props.summary_ym.substring(4, 6) + '月' }}</h1>
                                <div class="flex pl-4 mb-4 ml-auto max-w-sm ">
                                    <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">編集</button>
                                    <button class="flex mx-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
                                </div>
                                <div class="w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">カテゴリ</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">{{ member.member_name }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="border-t-4">(収入)</tr>
                                        <tr v-for="(category, categoryIndex) in categoryIncomeList" :key="categoryIncomeList.category_id">
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ category.category_name }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                                {{ commaSeparateOrBlank(paymentsIncomeArray[categoryIndex][memberIndex]) }}
                                            </td>
                                        </tr>
                                        <tr class="border-t-4">(支出)</tr>
                                        <tr v-for="(category, categoryIndex) in categoryExpneseList" :key="categoryExpneseList.category_id">
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ category.category_name }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                                {{ commaSeparateOrBlank(paymentsExpenseArray[categoryIndex][memberIndex]) }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
