<template>
    <h3 class="text-xl font-semibold tracking-wider mb-0">{{ title }}</h3>
    <div class="overflow-auto">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <BaseRowHeader
                    v-for="column in columns"
                    :key="column.name"
                    :class="column.size"
                >
                    {{ column.label }}
                </BaseRowHeader>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 overflow-y-scroll">
            <tr v-if="loading" class="text-center">
                <BaseRow :colspan="columns.length">
                    <Loading class="w-4 h-4 inline animate-spin" />
                    <span class="ml-2 font-bold">{{ $t('general.loading') }}</span>
                </BaseRow>
            </tr>
            <template v-if="!loading">
                <slot />
            </template>
            </tbody>
        </table>
    </div>
</template>

<script>
import BaseRowHeader from "./BaseRowHeader"
import Loading from "../Icons/Loading"
import BaseRow from "./BaseRow"

export default {
    name: "BaseTable",
    components: {BaseRow, Loading, BaseRowHeader},

    props: {
        loading: {
            type: Boolean,
            default: false
        },

        title: {
            type: String,
            default: ''
        },

        columns: {
            type: Array,
            required: true
        },
    }
}
</script>
