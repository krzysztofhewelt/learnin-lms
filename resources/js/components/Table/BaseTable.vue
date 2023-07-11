<template>
	<h3 class="mb-0 text-xl font-semibold tracking-wider">{{ title }}</h3>
	<div class="overflow-auto lg:rounded-lg lg:shadow-lg">
		<table class="w-full divide-y divide-gray-200">
			<thead class="hidden bg-gray-50 lg:table-header-group">
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
			<tbody class="divide-y divide-gray-200 overflow-y-scroll bg-transparent">
				<tr v-if="loading" class="text-center">
					<BaseRow :colspan="columns.length">
						<Loading class="inline h-4 w-4 animate-spin" />
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
import BaseRowHeader from '@/components/Table/BaseRowHeader.vue';
import Loading from '@/components/Icons/Loading.vue';
import BaseRow from '@/components/Table/BaseRow.vue';

export default {
	name: 'BaseTable',
	components: { BaseRow, Loading, BaseRowHeader },

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
		}
	}
};
</script>
