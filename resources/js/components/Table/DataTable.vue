<template>
	<div
		v-if="search || title"
		class="mb-2 flex w-full flex-col flex-wrap lg:flex-row"
		:class="{ 'justify-end': !title, 'justify-between': title }"
	>
		<h3 class="my-auto text-xl font-semibold tracking-wider">
			{{ title }}
		</h3>

		<div class="flex items-center">
			<div v-if="search">
				<InputWithIcon
					type="text"
					:placeholder="$t('general.search')"
					@input="$emit('searchAction', $event.target.value)"
				>
					<Search class="h-4 w-4" />
				</InputWithIcon>
			</div>
			<div class="ml-2" v-if="search">
				<button class="normal_btn" @click="$emit('refreshAction')">
					<Refresh class="m-1 h-4 w-4" />
				</button>
			</div>
			<div class="ml-2" v-if="canEdit">
				<button class="submit_btn" @click="$emit('addAction')">
					<Add class="m-1 h-4 w-4" />
				</button>
			</div>
		</div>
	</div>

	<BaseTable :columns="columns" :loading="loading">
		<tr
			v-for="(row, index) in tableData"
			:key="index"
			class="flex-no wrap mb-5 flex flex-col break-words rounded-lg lg:table-row lg:rounded-none"
		>
			<BaseRow v-for="column in columns.slice(0, -1)" :key="row['id'] || index">
				<div class="inline-block w-2/6 bg-gray-200 p-3 align-middle lg:hidden">
					{{ column.label }}
				</div>

				<div class="inline-block w-4/6 px-3 align-middle lg:px-0">
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
				</div>
			</BaseRow>
			<BaseRow>
				<div class="inline-block w-2/6 bg-gray-200 p-3 align-middle lg:hidden">
					{{ $t('general.actions') }}
				</div>

				<button
					@click="$emit('show', row['id'])"
					class="px-2 font-semibold text-gray-600 no-underline hover:text-gray-900"
				>
					{{ $t('general.show') }}
				</button>
				<button
					v-if="canEdit"
					@click="$emit('edit', row['id'])"
					class="px-2 font-semibold text-blue-600 no-underline hover:text-blue-900"
				>
					{{ $t('general.edit') }}
				</button>
				<button
					v-if="canEdit"
					@click="$emit('delete', row['id'])"
					class="px-2 font-semibold text-red-400 hover:text-red-600"
				>
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
import InputWithIcon from '@/components/BaseInputWithIcon.vue';
import Loading from '@/components/Icons/Loading.vue';
import Input from '@/components/BaseInput.vue';
import Search from '@/components/Icons/Search.vue';
import Add from '@/components/Icons/Add.vue';
import BaseRowHeader from '@/components/Table/BaseRowHeader.vue';
import BaseRow from '@/components/Table/BaseRow.vue';
import BaseTable from '@/components/Table/BaseTable.vue';
import ActiveBadge from '@/components/Table/Cells/ActiveBadge.vue';
import Date from '@/components/Table/Cells/Date.vue';
import Refresh from '@/components/Icons/Refresh.vue';

export default {
	name: 'DataTable',
	emits: ['searchAction', 'refreshAction', 'addAction', 'show', 'edit', 'delete'],

	components: {
		Refresh,
		BaseTable,
		BaseRow,
		BaseRowHeader,
		Add,
		Search,
		Input,
		Loading,
		InputWithIcon,
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
			default: ''
		},

		canEdit: {
			type: Boolean,
			default: true
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
	}
};
</script>
