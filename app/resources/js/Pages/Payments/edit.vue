<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {onBeforeMount, onBeforeUpdate, onUpdated, ref} from 'vue';

const props = defineProps({
    'summary_ym' : String,
    'members': Array,
    'memberCategories' : Array,
    'payments' : Array,
    'updatedPayment' : Array
})

const insertForm = useForm({
    summary_ym: props.summary_ym,
    group_id: "",
    member_id: "",
    category_id: "",
    payment_date: "",
    amount: "",
    payment_label: ""
})

const updateForm = useForm({
    payment_id: "",
    payment_date: "",
    amount: "",
    payment_label: ""
})

const deleteForm = useForm({
    payment_id: ""
})

onBeforeMount(() => {
    initBeforeRendering()
})

onBeforeUpdate(() => {
    initBeforeRendering()
})

const memberList = ref([])
let memberCategoryList = ref()
let paymentList = ref()
let newLinePayment = ref()
let IDdropdownOperator = ref(null)
const initBeforeRendering = () => {
    memberList.value = []
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
            paymentList[member_id][category_id] = {}
        }
        if (paymentList[member_id][category_id][categorized_payment_id] === undefined) {
            paymentList[member_id][category_id][categorized_payment_id] = {}
        }
        paymentList[member_id][category_id][categorized_payment_id] = paymentProperty
    })

    newLinePayment = {
        amount: '',
        payment_date: '',
        payment_label: ''
    }

    setMemberTabId(memberList.value[0].member_id)
    setCategoryTabId(Object.entries(memberCategoryList[memberList.value[0].member_id])[0][0])
    IDdropdownOperator.value = ''
}

const toggleDropdownOperator = arg => {
    if (IDdropdownOperator.value === arg) {
        IDdropdownOperator.value = ''
    }
    else {
        IDdropdownOperator.value = arg
    }
}

onUpdated(() => {
    if (props.updatedPayment !== null) {
        setMemberTabId(props.updatedPayment.member_id)
        setCategoryTabId(props.updatedPayment.category_id)
        setTimeout(forcusNewLineAmount, 0)
    }
})

const paymentItemTitle = ['明細番号', '金額', '日付', '名目', '操作']
const itemPrefixForQuerySelector = "item"
const forcusNewLineAmount = () => {
    const selector = '#' + newLineAmountPrefix + '_' + props.updatedPayment.member_id + '_' + props.updatedPayment.category_id + '_0_' + paymentItemTitle.indexOf('金額')
    document.querySelector(selector).focus()
    document.querySelector(selector).scrollIntoView(false)
}

const insertCommaOnEvent = ($event) => {
    $event.target.value = separateCommaOrBlank($event.target.value)
}

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

const removeCommaOnEvent = ($event) => {
    $event.target.value = removeComma($event.target.value)
}

const removeComma = arg => {
    return String(arg).replace(/,/g, '')
}

const separateHyphenEvent = ($event) => {
    $event.target.value = separateHyphen($event.target.value)
}

const separateHyphen = arg => {
    let argInsertedHyphen

    if (arg.match(/^\d{4}\-\d{2}\-\d{2}$/)) {
        argInsertedHyphen = arg
    }
    else {
        argInsertedHyphen = arg.substring(0, 4) + '-' + arg.substring(4, 6) + '-' + arg.substring(6, 8)
    }

    if (isNaN((new Date(argInsertedHyphen).getDate()))) {
        return ""
    }
    else {
        return argInsertedHyphen
    }
}

const getDisplayRowCount = (memberId, categoryId) => {
    if (paymentList[memberId] === undefined) {
        return 0
    }
    if (paymentList[memberId][categoryId] === undefined) {
        return 0
    }
    return Object.keys(paymentList[memberId][categoryId]).length
}

const newLineTitle = "(新規追加)"
const getCategorizedPaymentId = (memberId, categoryId, rowPayment) => {
    if (paymentList[memberId] === undefined || paymentList[memberId][categoryId] === undefined) {
        if (rowPayment === 1) {
            return newLineTitle
        }
        else {
            return ""
        }
    }
    else if (Object.entries(paymentList[memberId][categoryId])[rowPayment - 1] === undefined) {
        return ""
    }
    else if ((Object.entries(paymentList[memberId][categoryId])[rowPayment - 1])[0] === 'category_name') {
        return newLineTitle
    }
    else {
        return Number((Object.entries(paymentList[memberId][categoryId])[rowPayment - 1])[0])
    }
}

const getPaymentProperty = (memberId, categoryId, rowPayment, propertyName) => {
    const categorizedPaymentId = getCategorizedPaymentId(memberId, categoryId, rowPayment)
    if (typeof categorizedPaymentId === "number") {
        return paymentList[memberId][categoryId][categorizedPaymentId][propertyName]
    }
    else {
        return ""
    }
}

const setPaymentProperty = (memberId, categoryId, rowPayment, propertyName, value) => {
    const categorizedPaymentId = getCategorizedPaymentId(memberId, categoryId, rowPayment)
    if (typeof categorizedPaymentId === "number") {
        paymentList[memberId][categoryId][categorizedPaymentId][propertyName] = value
    }
}

const submitInsertPayment = (group_id, member_id, category_id) => {
    insertForm.group_id = group_id
    insertForm.member_id = member_id
    insertForm.category_id = category_id

    let selector = ''

    selector = '#' + newLineAmountPrefix + '_' + member_id + '_' + category_id + '_0_' + paymentItemTitle.indexOf('金額')
    insertForm.amount = removeComma(document.querySelector(selector).value)

    selector = '#' + newLineAmountPrefix + '_' + member_id + '_' + category_id + '_0_' + paymentItemTitle.indexOf('日付')
    insertForm.payment_date = separateHyphen(document.querySelector(selector).value)

    selector = '#' + newLineAmountPrefix + '_' + member_id + '_' + category_id + '_0_' + paymentItemTitle.indexOf('名目')
    insertForm.payment_label = document.querySelector(selector).value

    insertForm.post('/payments', insertForm)
}

const newLineAmountPrefix = "newLineAmount"
const submitUpdatePayment = (memberId, categoryId, rowPayment) => {
    updateForm.payment_id = getPaymentProperty(memberId, categoryId, rowPayment, 'payment_id')

    let selector = ''

    selector = '#' + itemPrefixForQuerySelector + '_' + memberId + '_' + categoryId + '_' + rowPayment + '_' + paymentItemTitle.indexOf('金額')
    updateForm.amount = removeComma(document.querySelector(selector).value)

    selector = '#' + itemPrefixForQuerySelector + '_' + memberId + '_' + categoryId + '_' + rowPayment + '_' + paymentItemTitle.indexOf('日付')
    updateForm.payment_date = separateHyphen(document.querySelector(selector).value)

    selector = '#' + itemPrefixForQuerySelector + '_' + memberId + '_' + categoryId + '_' + rowPayment + '_' + paymentItemTitle.indexOf('名目')
    updateForm.payment_label = document.querySelector(selector).value

    updateForm.put(route('payments.update', { payment: updateForm.payment_id }), updateForm)
}

let memberTabId = ref(null);
const setMemberTabId = arg => {
    memberTabId.value = String(arg)
    setCategoryTabId(Object.entries(memberCategoryList[arg])[0][0])
}

let categoryTabId = ref(null);
const setCategoryTabId = arg => {
    categoryTabId.value = String(arg)

    newLinePayment.amount = ''
    newLinePayment.payment_date = ''
    newLinePayment.payment_label = ''
}

const getNewPayment = propertyName => {
    return newLinePayment[propertyName]
}
const setNewPayment = (propertyName, value) => {
    newLinePayment[propertyName] = value
}

const submitDeletePayment = (memberId, categoryId, rowPayment) => {
    const memberObj = memberList.value.find(e => e.member_id === memberId);
    const memberCategoryObj = memberCategoryList[memberId][categoryId];
    deleteForm.payment_id = getPaymentProperty(memberId, categoryId, rowPayment, 'payment_id')

    deleteForm.delete(
        route(
            'payments.destroy',
            { payment: deleteForm.payment_id }
        ),
        {
            onBefore: () => confirm('本当に削除しますか？' + '\n'
                + 'メンバー名 : ' + memberObj.member_name + '\n'
                + 'カテゴリ名 : ' + memberCategoryObj.category_name + '\n'
                + '行番号 : ' + rowPayment)
        }
    )
}
</script>

<style scoped>
.radius {
    border-collapse: separate;
    border-spacing: 0;
}
/* 左上 */
.radius thead th:first-child {
    border-radius: 10px 0 0 0;
}
/* 右上 */
.radius thead th:last-child {
    border-radius: 0 10px 0 0;
}
/* 左下 */
.radius tbody tr:last-child td:first-child {
    border-radius: 0 0 0 10px;
}
/* 右下 */
.radius tbody tr:last-child td:last-child {
    border-radius: 0 0 10px 0;
}
/* 内部の上のborderを取りやめ。設定しているborderを削除でもOK */
.radius tbody td {
    border-top-width: 0;
}
/* 左のborderを取りやめ。設定しているborderを削除でもOK */
.radius th,
.radius td {
    border-left-width: 0;
}
/* ただし、一番左は必要 */
.radius th:first-child,
.radius td:first-child {
    border-left-width: 1px;
}
</style>

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
                                    <Link class="flex mx-auto text-black bg-slate-100 border-2 py-2 px-6 focus:outline-none hover:bg-slate-200 rounded" :href="route('payments.showSummary', { summary_ym: props.summary_ym })">内訳へ戻る</Link>
                                </div>

                                <div class="bg-white">
                                    <nav class="flex flex-col sm:flex-row">
                                        <button
                                            v-for="(member, memberKey) in memberList"
                                            @click="setMemberTabId(member.member_id)"
                                            v-bind:class="{'text-blue-500 border-b-2 font-medium border-blue-500': memberTabId === String(member.member_id)}"
                                            class="text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none"
                                        >
                                            {{ member.member_name }}
                                        </button>
                                    </nav>
                                </div>
                                <div class="bg-white mb-4">
                                    <nav class="flex flex-col sm:flex-row">
                                        <button
                                            v-for="(category, categoryKey) in memberCategoryList[memberTabId]"
                                            @click="setCategoryTabId(categoryKey)"
                                            v-bind:class="{'text-blue-500 border-b-2 font-medium border-blue-500': categoryTabId === String(categoryKey)}"
                                            class="text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none"
                                        >
                                            {{ category.category_name }}
                                        </button>
                                    </nav>
                                </div>

                                <div v-for="(member, memberKey) in memberList">
                                    <div v-for="(category, categoryKey) in memberCategoryList[member.member_id]" class="flex justify-center">
                                        <table
                                            class="table-auto w-full text-left whitespace-no-wrap radius"
                                            v-show="String(member.member_id) === memberTabId && String(categoryKey) === categoryTabId"
                                        >
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="border border-gray-400 px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 text-center"
                                                        v-for="(item, itemKey) in paymentItemTitle"
                                                    >
                                                        {{ item }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="rowPayment in getDisplayRowCount(member.member_id, categoryKey)">
                                                    <td
                                                        class="border border-gray-400 border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end"
                                                        v-for="(item, itemKey) in paymentItemTitle"
                                                        nowrap
                                                    >
                                                        <div
                                                            v-if="item === '明細番号'"
                                                            v-bind:id="itemPrefixForQuerySelector + '_' + member.member_id + '_' + categoryKey + '_' + rowPayment + '_' + paymentItemTitle.indexOf('明細番号')"
                                                        >
                                                            {{
                                                                getCategorizedPaymentId(
                                                                    member.member_id,
                                                                    categoryKey,
                                                                    rowPayment
                                                                )
                                                            }}
                                                        </div>
                                                        <div v-if="item === '金額'" class="flex justify-center">
                                                            <input
                                                                type="text"
                                                                class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out w-full"
                                                                :value="
                                                                    separateCommaOrBlank(
                                                                        getPaymentProperty(
                                                                            member.member_id,
                                                                            categoryKey,
                                                                            rowPayment,
                                                                            'amount'
                                                                        )
                                                                    )
                                                                "
                                                                @input="
                                                                    setPaymentProperty(
                                                                        member.member_id,
                                                                        categoryKey,
                                                                        rowPayment,
                                                                        'amount',
                                                                        removeComma($event.target.value)
                                                                    )
                                                                "
                                                                @keypress.enter="
                                                                    submitUpdatePayment(
                                                                        member.member_id,
                                                                        categoryKey,
                                                                        rowPayment
                                                                     )
                                                                "
                                                                @focus="removeCommaOnEvent($event)"
                                                                @blur="insertCommaOnEvent($event)"
                                                                v-bind:id="itemPrefixForQuerySelector + '_' + member.member_id + '_' + categoryKey + '_' + rowPayment + '_' + paymentItemTitle.indexOf('金額')"
                                                            >
                                                        </div>
                                                        <div v-if="item === '日付'" class="flex justify-center">
                                                            <input
                                                                type="text"
                                                                class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out w-full"
                                                                :value="
                                                                    getPaymentProperty(
                                                                        member.member_id,
                                                                        categoryKey,
                                                                        rowPayment,
                                                                        'payment_date'
                                                                    )
                                                                "
                                                                @input="
                                                                    setPaymentProperty(
                                                                        member.member_id,
                                                                        categoryKey,
                                                                        rowPayment,
                                                                        'payment_date',
                                                                        separateHyphen($event.target.value)
                                                                    )
                                                                "
                                                                @keypress.enter="
                                                                    submitUpdatePayment(
                                                                        member.member_id,
                                                                        categoryKey,
                                                                        rowPayment
                                                                     )
                                                                "
                                                                @blur="separateHyphenEvent($event)"
                                                                v-bind:id="itemPrefixForQuerySelector + '_' + member.member_id + '_' + categoryKey + '_' + rowPayment + '_' + paymentItemTitle.indexOf('日付')"
                                                            >
                                                        </div>
                                                        <div v-if="item === '名目'" class="flex justify-center">
                                                            <input
                                                                type="text"
                                                                class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out w-full"
                                                                :value="
                                                                    getPaymentProperty(
                                                                        member.member_id,
                                                                        categoryKey,
                                                                        rowPayment,
                                                                        'payment_label'
                                                                    )
                                                                "
                                                                @input="
                                                                    setPaymentProperty(
                                                                        member.member_id,
                                                                        categoryKey,
                                                                        rowPayment,
                                                                        'payment_label',
                                                                        $event.target.value
                                                                    )
                                                                "
                                                                @keypress.enter="
                                                                    submitUpdatePayment(
                                                                        member.member_id,
                                                                        categoryKey,
                                                                        rowPayment
                                                                     )
                                                                "
                                                                v-bind:id="itemPrefixForQuerySelector + '_' + member.member_id + '_' + categoryKey + '_' + rowPayment + '_' + paymentItemTitle.indexOf('名目')"
                                                            >
                                                        </div>
                                                        <div v-if="item === '操作'">
                                                            <div class="flex justify-center items-center relative">
                                                                <div class="flex">
                                                                    <!-- デフォルトのボタン -->
                                                                    <input
                                                                        type="button"
                                                                        class="flex text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none focus:ring hover:bg-indigo-600 rounded-l text-lg z-10"
                                                                        value="更新"
                                                                        @click="
                                                                            submitUpdatePayment(
                                                                                member.member_id,
                                                                                categoryKey,
                                                                                rowPayment
                                                                             )
                                                                        "
                                                                    >
                                                                    <!-- ブルダウンボタン -->
                                                                    <input
                                                                        type="button"
                                                                        class="text-indigo bg-indigo-200 border-0 py-2 px-3 focus:outline-none hover:bg-indigo-300 rounded-r text-lg z-0"
                                                                        value="▽"
                                                                        @click="toggleDropdownOperator(itemPrefixForQuerySelector + '_' + member.member_id + '_' + categoryKey + '_' + rowPayment + '_' + paymentItemTitle.indexOf('操作'))"
                                                                    >
                                                                </div>

                                                                <!-- プルダウンメニュー -->
                                                                <div
                                                                    class="absolute top-0 left-auto right-auto mr-9 ring-opacity-5 z-20"
                                                                    v-show="IDdropdownOperator === itemPrefixForQuerySelector + '_' + member.member_id + '_' + categoryKey + '_' + rowPayment + '_' + paymentItemTitle.indexOf('操作')"
                                                                >
                                                                    <input
                                                                        type="button"
                                                                        class="flex mx-auto text-slate-600 bg-slate-200 border-0 py-2 px-4 focus:outline-none focus:ring rounded-l text-lg shadow-2xl shadow-black"
                                                                        value="操作選択"
                                                                    >
                                                                    <!-- 更新ボタン -->
                                                                    <input
                                                                        type="button"
                                                                        class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none focus:ring hover:bg-indigo-600 rounded text-lg shadow-2xl shadow-black"
                                                                        value="更新"
                                                                        @click="
                                                                            submitUpdatePayment(
                                                                                member.member_id,
                                                                                categoryKey,
                                                                                rowPayment
                                                                             )
                                                                        "
                                                                    >
                                                                    <!-- 削除ボタン -->
                                                                    <input
                                                                        type="button"
                                                                        class="flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none focus:ring hover:bg-red-600 rounded text-lg shadow-2xl shadow-black"
                                                                        value="削除"
                                                                        @click="
                                                                            submitDeletePayment(
                                                                                member.member_id,
                                                                                categoryKey,
                                                                                rowPayment
                                                                            )
                                                                        "
                                                                    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        class="border border-gray-400 border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end"
                                                        v-for="(item, itemKey) in paymentItemTitle"
                                                        nowrap
                                                    >
                                                        <div
                                                            v-if="item === '明細番号'"
                                                            v-bind:id="newLineAmountPrefix + '_' + member.member_id + '_' + categoryKey + '_0_' + paymentItemTitle.indexOf('明細番号')"
                                                        >
                                                            {{ newLineTitle }}
                                                        </div>
                                                        <div v-if="item === '金額'" class="flex justify-center">
                                                            <input
                                                                type="text"
                                                                class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out w-full"
                                                                :value="separateCommaOrBlank(getNewPayment('amount'))"
                                                                @input="setNewPayment('amount', removeComma($event.target.value))"
                                                                @keypress.enter="
                                                                    separateCommaOrBlank(
                                                                        submitInsertPayment(
                                                                            member.group_id,
                                                                            member.member_id,
                                                                            categoryKey
                                                                        )
                                                                    )
                                                                "
                                                                @focus="removeCommaOnEvent($event)"
                                                                @blur="insertCommaOnEvent($event)"
                                                                v-bind:id="newLineAmountPrefix + '_' + member.member_id + '_' + categoryKey + '_0_' + paymentItemTitle.indexOf('金額')"
                                                            >
                                                        </div>
                                                        <div v-if="item === '日付'" class="flex justify-center">
                                                            <input
                                                                type="text"
                                                                class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out w-full"
                                                                :value="getNewPayment('payment_date')"
                                                                @input="setNewPayment('payment_date', separateHyphen($event.target.value))"
                                                                @keypress.enter="
                                                                    submitInsertPayment(
                                                                        member.group_id,
                                                                        member.member_id,
                                                                        categoryKey
                                                                    )
                                                                "
                                                                @blur="separateHyphenEvent($event)"
                                                                v-bind:id="newLineAmountPrefix + '_' + member.member_id + '_' + categoryKey + '_0_' + paymentItemTitle.indexOf('日付')"
                                                            >
                                                        </div>
                                                        <div v-if="item === '名目'" class="flex justify-center">
                                                            <input
                                                                type="text"
                                                                class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out w-full"
                                                                :value="getNewPayment('payment_label')"
                                                                @input="setNewPayment('payment_label', $event.target.value)"
                                                                @keypress.enter="
                                                                    submitInsertPayment(
                                                                        member.group_id,
                                                                        member.member_id,
                                                                        categoryKey
                                                                    )
                                                                "
                                                                v-bind:id="newLineAmountPrefix + '_' + member.member_id + '_' + categoryKey + '_0_' + paymentItemTitle.indexOf('名目')"
                                                            >
                                                        </div>
                                                        <div v-if="item === '操作'">
                                                            <input
                                                                type="button"
                                                                class="flex mx-auto text-white bg-green-500 border-0 py-2 px-8 focus:outline-none focus:ring focus:ring-green-300 hover:bg-green-600 rounded text-lg"
                                                                value="追加"
                                                                @click="
                                                                    submitInsertPayment(
                                                                        member.group_id,
                                                                        member.member_id,
                                                                        categoryKey
                                                                    )
                                                                "
                                                            >
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
