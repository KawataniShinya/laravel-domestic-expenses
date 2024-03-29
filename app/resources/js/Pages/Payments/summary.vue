<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const form = useForm({});

const props = defineProps({
    'summary_ym' : String,
    'members' : Array,
    'categories' : Array,
    'paymentsIncome' : Array,
    'paymentsExpense' : Array,
    'paymentExpenseByMemberLastMonth' : Array,
    'paymentExpenseByMember2MonthAgo' : Array,
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
        incomeTotalList.value.push({
            member_id: income.member_id,
            category_id: income.category_id,
            amount: income.amount
        })
    })
    props.paymentsExpense.forEach(expense => {
        expenseTotalList.value.push({
            member_id: expense.member_id,
            category_id: expense.category_id,
            amount: expense.amount
        })
    })

    // メンバー別過去支出額
    props.paymentExpenseByMemberLastMonth.forEach(expensePast => {
        expenseLastList.value.push(expensePast.amount)
    })
    props.paymentExpenseByMember2MonthAgo.forEach(expensePast => {
        expense2MonthsAgoList.value.push(expensePast.amount)
    })

    // 収入テーブルへの2次元配列マッピング
    paymentsIncomeArray = new Array(categoryIncomeIndexArray.value.length)
    for(let i=0; i<paymentsIncomeArray.length; i++) {
        paymentsIncomeArray[i] = new Array(memberIndexArray.value.length).fill("")
    }
    // メンバー別収入総額
    incomeTotalList.value.forEach(income => {
        paymentsIncomeArray[categoryIncomeIndexArray.value.indexOf(income.category_id)][memberIndexArray.value.indexOf(income.member_id)] = income.amount;
        sumPaymentIncome.value += Number(income.amount);
    })

    // 支出テーブルへの2次元配列マッピング
    paymentsExpenseArray = new Array(categoryExpenseIndexArray.value.length)
    for(let i=0; i<paymentsExpenseArray.length; i++) {
        paymentsExpenseArray[i] = new Array(memberIndexArray.value.length).fill("")
    }
    // メンバー別支出総額
    expenseTotalList.value.forEach(expence => {
        paymentsExpenseArray[categoryExpenseIndexArray.value.indexOf(expence.category_id)][memberIndexArray.value.indexOf(expence.member_id)] = expence.amount;
        sumPaymentExpense.value += Number(expence.amount)
    })
})
const memberList = ref([])
const memberIndexArray = ref([])
const categoryIncomeList = ref([])
const categoryIncomeIndexArray = ref([])
const categoryExpneseList = ref([])
const categoryExpenseIndexArray = ref([])
const incomeTotalList = ref([])
const expenseTotalList = ref([])
const expenseLastList = ref([])
const expense2MonthsAgoList = ref([])
let paymentsIncomeArray = ref()
let sumPaymentIncome = ref(0)
let paymentsExpenseArray = ref()
let sumPaymentExpense = ref(0)

const separateCommaOrBlank = arg => {
    if (arg === "") {
        return ""
    }
    else if (isNaN(arg)) {
        return arg
    }
    else {
        return Number(arg).toLocaleString()
    }
}

const sumHorizontal = paymentArray => {
    let sum = 0;
    paymentArray.forEach(payment => {
        sum += Number(payment);
    })
    return sum;
}

const sumVertical = (paymentArray, memberIndex) => {
    let sum = 0;
    paymentArray.forEach(payment => {
        sum += Number(payment[memberIndex]);
    })
    return sum;
}

const deletePaymentsRelatedHistory = () => {
    form.delete(
        route(
            'payments.destroy-related',
            { summary_ym: props.summary_ym }
        ),
        {
            onBefore: () => confirm('本当に削除しますか？')
        }
    )
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
                            <div class="container px-5 py-4 mx-auto" style="min-height:500px;">
                                <h1 class="text-3xl">{{ props.summary_ym.substring(0, 4) + '年' + props.summary_ym.substring(4, 6) + '月' }}</h1>
                                <div class="flex pl-4 mb-4 ml-auto max-w-sm ">
                                    <Link class="flex mx-auto text-black bg-slate-100 border-2 py-2 px-6 focus:outline-none hover:bg-slate-200 rounded" :href="route('payments.index', {})">一覧へ戻る</Link>
                                    <Link v-if="props.members.length !== 0 || props.categories.length !== 0" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" :href="route('payments.editPayments', {summary_ym: props.summary_ym})">編集</Link>
                                    <Link v-else class="flex mx-auto text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded" :href="route('payments.editPayments', {summary_ym: props.summary_ym})">新規</Link>
                                    <button v-if="props.members.length !== 0 || props.categories.length !== 0" class="flex mx-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded" @click="deletePaymentsRelatedHistory()">削除</button>
                                </div>
                                <div class="w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 rounded-tl rounded-bl">カテゴリ</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 text-center" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">{{ member.member_name }}</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 text-center">合計</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="border-t-4 bg-gray-100"><td>(収入)</td><td v-for="(member, memberIndex) in memberList" :key="memberList.member_id"></td><td></td></tr>
                                        <tr v-for="(category, categoryIndex) in categoryIncomeList" :key="categoryIncomeList.category_id">
                                            <th class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end ">{{ category.category_name }}</th>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                                {{ separateCommaOrBlank(paymentsIncomeArray[categoryIndex][memberIndex]) }}
                                            </td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-amber-100">
                                                {{ separateCommaOrBlank(sumHorizontal(paymentsIncomeArray[categoryIndex])) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-amber-100">合計</th>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-amber-100" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                                {{ separateCommaOrBlank(sumVertical(paymentsIncomeArray, memberIndex)) }}
                                            </td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-amber-100">
                                                {{ separateCommaOrBlank(sumPaymentIncome) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-yellow-200">(収入-先月支出)</th>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-yellow-200" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                                {{ separateCommaOrBlank(sumVertical(paymentsIncomeArray, memberIndex) - expenseLastList[memberIndex]) }}
                                            </td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-yellow-200"></td>
                                        </tr>
                                        <tr>
                                            <th class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-yellow-200">(収入-先々月支出)</th>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-yellow-200" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                                {{ separateCommaOrBlank(sumVertical(paymentsIncomeArray, memberIndex) - expense2MonthsAgoList[memberIndex]) }}
                                            </td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-yellow-200"></td>
                                        </tr>
                                        <tr class="border-t-4 bg-gray-100"><td>(支出)</td><td v-for="(member, memberIndex) in memberList" :key="memberList.member_id"></td><td></td></tr>
                                        <tr v-for="(category, categoryIndex) in categoryExpneseList" :key="categoryExpneseList.category_id">
                                            <th class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ category.category_name }}</th>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                                {{ separateCommaOrBlank(paymentsExpenseArray[categoryIndex][memberIndex]) }}
                                            </td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-amber-100">
                                                {{ separateCommaOrBlank(sumHorizontal(paymentsExpenseArray[categoryIndex])) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-amber-100">合計</th>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-amber-100" v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                                {{ separateCommaOrBlank(sumVertical(paymentsExpenseArray, memberIndex)) }}
                                            </td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-amber-100">
                                                {{ separateCommaOrBlank(sumPaymentExpense) }}
                                            </td>
                                        </tr>
                                        <tr class="border-t-4">
                                            <th class="border-t-2 border-b-2 border-gray-200 px-4 py-3 bg-orange-100">(収支)</th>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-orange-100" v-for="(member, memberIndex) in memberList" :key="memberList.member_id"></td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end bg-orange-100">
                                                {{ separateCommaOrBlank(sumPaymentIncome - sumPaymentExpense) }}
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
