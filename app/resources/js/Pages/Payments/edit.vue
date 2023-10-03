<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {onBeforeMount, ref} from 'vue';

const props = defineProps({
    'summary_ym' : String,
    'members': Array,
    'memberCategories' : Array,
    'payments' : Array
})

const form = useForm({
    payment_id: "",
    summary_ym: props.summary_ym,
    group_id: "",
    member_id: "",
    category_id: "",
    categorized_payment_id: "",
    payment_date: "",
    amount: "",
    payment_label: ""
})

onBeforeMount(() => {
    props.members.forEach(member => {
        memberList.value.push({
            member_id: member.member_id,
            member_name: member.member_name,
            group_id: member.group_id
        })
    })

    memberCategoryList = {}
    props.memberCategories.forEach(memberCategory => {
        const member_id = memberCategory.member_id
        const category_id = memberCategory.category_id
        const memberCategoryProperty = {
            category_name: memberCategory.category_name,
            display_order: memberCategory.display_order,
            income_flg: memberCategory.income_flg
        }

        if (memberCategoryList[member_id] === undefined) {
            memberCategoryList[member_id] = {}
        }
        if (memberCategoryList[member_id][category_id] === undefined) {
            memberCategoryList[member_id][category_id] = {}
        }
        memberCategoryList[member_id][category_id] = memberCategoryProperty
    })

    paymentList = {}
    props.payments.forEach(payment => {
        const member_id = payment.member_id
        const category_id = payment.category_id
        const categorized_payment_id = payment.categorized_payment_id
        const paymentProperty = {
            payment_id: payment.payment_id,
            payment_date: payment.payment_date,
            amount: payment.amount,
            payment_label: payment.payment_label
        }

        if (paymentList[member_id] === undefined) {
            paymentList[member_id] = {}
        }
        if (paymentList[member_id][category_id] === undefined) {
            paymentList[member_id][category_id] = {category_name: payment.category_name}
        }
        if (paymentList[member_id][category_id][categorized_payment_id] === undefined) {
            paymentList[member_id][category_id][categorized_payment_id] = {}
        }
        paymentList[member_id][category_id][categorized_payment_id] = paymentProperty
    })
    for (const [member_id, CategorizedPyments] of Object.entries(paymentList)) {
        let paymentCount = 0
        for (const [category_id, payments] of Object.entries(CategorizedPyments)) {
            if (paymentCount < Object.keys(payments).length) {
                paymentCount = Object.keys(payments).length
            }
        }
        paymentList[member_id].max_payment_count = paymentCount
    }
})
const memberList = ref([])
let memberCategoryList = ref()
let paymentList = ref()
const paymentItemTitle = ['明細番号', '金額', '日付', '名目', '操作']

const commaSeparateOrBlank = args => {
    if (args !== "") {
        return Number(args).toLocaleString()
    }
    else {
        return "";
    }
}

const getCategoryId = (memberId, columnCountWholeTable) => {
    const itemOffset = Math.floor((columnCountWholeTable - 1) / paymentItemTitle.length)
    return Object.keys(memberCategoryList[memberId])[itemOffset]
}

const getCategorizedPaymentId = (memberId, categoryId, rowPayment) => {
    if (paymentList[memberId][categoryId] === undefined  ||
        Object.entries(paymentList[memberId][categoryId])[rowPayment - 1] === undefined) {
        return ""
    }
    else {
        const payment = Object.entries(paymentList[memberId][categoryId])[rowPayment - 1]
        const categorizedPaymentId = payment[0]
        if (categorizedPaymentId === 'category_name') {
            return ""
        }
        else {
            return categorizedPaymentId
        }
    }
}

const getPaymentProperty = (memberId, categoryId, rowPayment, propertyName) => {
    const categorizedPaymentId = getCategorizedPaymentId(memberId, categoryId, rowPayment)
    if (categorizedPaymentId === "") {
        return ""
    }
    else {
        return paymentList[memberId][categoryId][categorizedPaymentId][propertyName]
    }
}

const setTempItem = arg => {
    tempItem = arg
}
let tempItem

const submitUpdatePayment = ($event, currentTitleName, payment_id, group_id, member_id, category_id) => {
    let currentTdElement = $event.target.parentNode.parentNode
    for (let i=0; i < paymentItemTitle.indexOf(currentTitleName); i++) {
        currentTdElement = currentTdElement.previousElementSibling
    }

    form.payment_id = payment_id
    form.group_id = group_id
    form.member_id = member_id
    form.category_id = category_id

    form.categorized_payment_id = currentTdElement.querySelector('div').innerText

    currentTdElement = currentTdElement.nextElementSibling
    form.amount = currentTdElement.querySelector('div input').value

    currentTdElement = currentTdElement.nextElementSibling
    form.payment_date = currentTdElement.querySelector('div input').value

    currentTdElement = currentTdElement.nextElementSibling
    form.payment_label = currentTdElement.querySelector('div input').value

    console.log(form)
}
</script>

<template>
    <Head title="収支登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                収支登録
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-900 body-font">
                            <div class="container px-5 py-4 mx-auto" style="min-height:500px; max-width:1880px;">
                                <h1 class="text-3xl">{{ props.summary_ym.substring(0, 4) + '年' + props.summary_ym.substring(4, 6) + '月' }}</h1>
                                <div class="flex pl-4 mb-4 ml-auto max-w-sm ">
                                    <Link class="flex mx-auto text-black bg-slate-100 border-2 py-2 px-6 focus:outline-none hover:bg-slate-200 rounded" :href="route('payments.index', {})">一覧へ戻る</Link>
                                </div>
                                <div class="w-full mx-auto overflow-auto border border-gray-400">
                                    <table class="table-auto w-full text-left whitespace-no-wrap my-3 mb-6">
                                        <tr>
                                            <td v-for="(member, memberKey) in memberList" class="align-top">
                                                <div class="mx-3">
                                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                                        <thead>
                                                            <tr>
                                                                <th class="border border-gray-400 px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-400 text-center" :colspan="Object.keys(memberCategoryList[member.member_id]).length * paymentItemTitle.length">{{ member.member_name }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="border border-gray-400 px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-300 text-center" :colspan="5" v-for="(category, categoryKey) in memberCategoryList[member.member_id]" nowrap>{{ category.category_name }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="border border-gray-400 px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 text-center" v-for="columnCountWholeTable in Object.keys(memberCategoryList[member.member_id]).length * paymentItemTitle.length" nowrap>{{ paymentItemTitle[(columnCountWholeTable - 1) % paymentItemTitle.length] }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody v-if="paymentList[member.member_id] !== undefined">
                                                            <tr v-for="rowPayment in paymentList[member.member_id].max_payment_count">
                                                                <td class="border border-gray-400 border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end" v-for="columnCountWholeTable in Object.keys(memberCategoryList[member.member_id]).length * paymentItemTitle.length" nowrap>
                                                                    <div v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '明細番号'">
                                                                        {{ getCategorizedPaymentId(member.member_id, getCategoryId(member.member_id, columnCountWholeTable), rowPayment) }}
                                                                    </div>
                                                                    <div v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '金額'">
                                                                        {{ setTempItem(getPaymentProperty(member.member_id, getCategoryId(member.member_id, columnCountWholeTable), rowPayment, 'amount')) }}
                                                                        <input
                                                                            type="text"
                                                                            class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                                                            :value="tempItem"
                                                                            @keydown.enter="submitUpdatePayment($event, '金額', getPaymentProperty(member.member_id, getCategoryId(member.member_id, columnCountWholeTable), rowPayment, 'payment_id'), member.group_id, member.member_id, getCategoryId(member.member_id, columnCountWholeTable))">
                                                                    </div>
                                                                    <div v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '日付'">
                                                                        {{ setTempItem(getPaymentProperty(member.member_id, getCategoryId(member.member_id, columnCountWholeTable), rowPayment, 'payment_date')) }}
                                                                        <input
                                                                            type="text"
                                                                            class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                                                            :value="tempItem"
                                                                            @keydown.enter="submitUpdatePayment($event, '日付', getPaymentProperty(member.member_id, getCategoryId(member.member_id, columnCountWholeTable), rowPayment, 'payment_id'), member.group_id, member.member_id, getCategoryId(member.member_id, columnCountWholeTable))">
                                                                    </div>
                                                                    <div v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '名目'">
                                                                        {{ setTempItem(getPaymentProperty(member.member_id, getCategoryId(member.member_id, columnCountWholeTable), rowPayment, 'payment_label')) }}
                                                                        <input
                                                                            type="text"
                                                                            class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                                                            :value="tempItem"
                                                                            @keydown.enter="submitUpdatePayment($event, '名目', getPaymentProperty(member.member_id, getCategoryId(member.member_id, columnCountWholeTable), rowPayment, 'payment_id'), member.group_id, member.member_id, getCategoryId(member.member_id, columnCountWholeTable))">
                                                                    </div>
                                                                    <div v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '操作'">
                                                                        <input
                                                                            type="button"
                                                                            class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                                                                            value="更新"
                                                                            @click="submitUpdatePayment($event, '操作', getPaymentProperty(member.member_id, getCategoryId(member.member_id, columnCountWholeTable), rowPayment, 'payment_id'), member.group_id, member.member_id, getCategoryId(member.member_id, columnCountWholeTable))">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tbody v-if="paymentList[member.member_id] === undefined">
                                                            <tr>
                                                                <td class="border border-gray-400 border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end" v-for="columnCountWholeTable in Object.keys(memberCategoryList[member.member_id]).length * paymentItemTitle.length" nowrap>
                                                                    <div v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '明細番号'">(新規追加)</div>
                                                                    <input type="text" v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '金額'" class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                                    <input type="text" v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '日付'" class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                                    <input type="text" v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '名目'" class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                                    <div v-if="paymentItemTitle[(columnCountWholeTable-1) % paymentItemTitle.length] === '操作'">(button)</div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
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
