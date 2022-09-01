<template>
    <div v-if="search || title"
        class="w-full flex flex-wrap items-center mb-2"
        :class="{'justify-end': !title, 'justify-between': title}">

        <h3 class="text-xl font-semibold tracking-wider mb-0">{{ title }}</h3>

        <div class="flex items-center">
            <div v-if="search">
                <InputWithIcon
                    type="text"
                    :placeholder="$t('general.search')"
                    @input="$emit('searchAction', $event.target.value)"
                >
                    <Search class="w-4 h-4" />
                </InputWithIcon>
            </div>
            <div class="ml-2" v-if="search">
                <button class="manage_btn"
                        @click="$emit('refreshAction')"
                >
                    <Refresh class="w-5 h-5 m-1 text-black" />
                </button>
            </div>
            <div class="ml-2">
                <button class="normal_btn"
                        @click="$emit('addAction')">
                    <Add class="w-4 h-4 m-1" />
                </button>
            </div>
        </div>
    </div>


    <BaseTable
         :columns="columns"
         :loading="loading"
    >
        <tr v-for="(row,index) in tableData" :key="index">
            <BaseRow v-for="column in columns.slice(0,-1)" :key="row['id'] || index">
                <template v-if="column.component">
                    <component :is="column.component" :value="row[column.name]"></component>
                </template>
                <template v-else>
                    <template v-if="column.subName">
                        {{ row[column.name][column.subName] }}
                    </template>
                    <template v-else>
                        {{ row[column.name] }}
                    </template>
                </template>
            </BaseRow>
            <BaseRow>
                <button @click="$emit('show', row['id'])" class="px-2 font-semibold text-gray-600 hover:text-gray-900 no-underline">
                    {{ $t('general.show') }}
                </button>
                <button @click="$emit('edit', row['id'])" class="px-2 font-semibold text-blue-600 hover:text-blue-900 no-underline">
                    {{ $t('general.edit') }}
                </button>
                <button @click="$emit('delete', row['id'])" class="px-2 font-semibold text-red-400 hover:text-red-600">
                    {{ $t('general.delete') }}
                </button>
            </BaseRow>
        </tr>

        <tr v-if="tableData.length === 0" class="text-center font-bold">
            <BaseRow :colspan="columns.length">
                {{ $t('general.no_data') }}
            </BaseRow>
        </tr>
    </BaseTable>
</template>

<script>
import InputWithIcon from "../BaseInputWithIcon"
import Loading from "../Icons/Loading"
import Input from "../BaseInput"
import Search from "../Icons/Search"
import Add from "../Icons/Add"
import BaseRowHeader from "./BaseRowHeader"
import BaseRow from "./BaseRow"
import BaseTable from "./BaseTable"
import ActiveBadge from "./Cells/ActiveBadge"
import Date from "./Cells/Date"
import Refresh from "../Icons/Refresh";

export default {
    name: "DataTable",
    emits: ['searchAction', 'refreshAction', 'addAction', 'show', 'edit', 'delete'],

    components: {
        Refresh,
        BaseTable,
        BaseRow,
        BaseRowHeader,
        Add, Search, Input, Loading, InputWithIcon,
        ActiveBadge,
        Date
    },

    props: {
        loading: {
            type: Boolean,
            default: false
        },

        title: {
            type: String,
            default: ""
        },

        search: {
            type: Boolean,
            default: false
        },

        columns: {
            type: Array,
            required: true
        },

        tableData: {
            type: Array,
            required: true
        }
    },
}
</script>
