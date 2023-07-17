<template>
	<div v-for="mark in infiniteMarks.data" :key="mark.id" class="mb-6">
		<div
			class="relative grid w-full grid-flow-row grid-cols-1 space-y-3 rounded-lg bg-white p-6 text-center shadow-xl lg:grid-cols-5 lg:space-y-0 lg:divide-x"
		>
			<div class="break-words px-4">
				<router-link
					:to="{ name: 'TasksView', params: { id: mark.task.id } }"
					class="m-auto block text-xl font-bold text-purple-800 no-underline hover:text-purple-500"
					>{{ mark.task.name }}</router-link
				>
				<div class="text-gray-500">
					<b>{{ $t('course.course') }}:</b>
					{{ mark.task.course.name }}
				</div>
				<div class="text-gray-500">
					<b>{{ $t('course.category') }}:</b>
					{{ mark.task.category.name }}
				</div>
			</div>
			<div class="px-4">
				<div class="text-xl font-bold">{{ $t('mark.mark') }}</div>
				<div class="text-2xl">{{ mark.mark }}</div>
			</div>
			<div class="px-4">
				<div class="text-xl font-bold">{{ $t('mark.points') }}</div>
				<div class="text-2xl">
					{{ mark.points }}
					<span class="text-zinc-500">/ {{ mark.task.max_points }}</span>
				</div>
			</div>
			<div class="break-words px-4">
				<div class="text-xl font-bold">
					{{ $t('mark.date_of_mark_label') }}
				</div>
				<div class="text-2xl">
					{{ getFormattedMarkDate(mark.updated_at) }}
				</div>
			</div>
			<div class="px-4">
				<div class="text-xl font-bold">{{ $t('mark.comment') }}</div>
				<button
					class="m-auto text-2xl font-bold text-purple-800 no-underline hover:text-purple-500"
					v-if="mark.description !== null"
					@click="mark.showDesc = !mark.showDesc"
				>
					{{
						mark.showDesc === false || !mark.showDesc
							? $t('general.show')
							: $t('general.hide')
					}}
				</button>
				<div v-else class="text-center text-xl font-bold">
					{{ $t('general.not_available') }}
				</div>
			</div>
		</div>
		<Transition name="slidedown">
			<div class="sticky w-full rounded-lg bg-gray-100 p-6" v-if="mark.showDesc">
				<h1 class="mb-2 text-xl font-bold">{{ $t('mark.comment') }}</h1>
				<p class="whitespace-pre-line">
					{{ mark.description }}
				</p>
			</div>
		</Transition>
	</div>

	<div class="text-center">
		<div v-if="loading" class="mt-6 text-2xl">
			<Loading class="inline h-4 w-4 animate-spin" />
			{{ $t('general.loading') }}
		</div>

		<div
			v-if="marks.data && marks.data.length === 0 && !loading"
			class="mt-6 text-2xl font-bold"
		>
			{{ $t('general.no_data') }}
		</div>

		<div
			ref="load_more"
			v-observe-visibility="{ callback: loadMoreMarks, throttle: 300 }"
			v-if="!loading && page < marks.last_page"
		></div>

		<div
			v-if="page === marks.last_page && marks.data.length > 0 && !loading"
			class="mt-6 text-2xl font-bold"
		>
			{{ $t('mark.all_marks') }}
		</div>
	</div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import Loading from '@/components/Icons/Loading.vue';

export default {
	name: 'UserMarks',
	components: { Loading },
	data() {
		return {
			infiniteMarks: [],
			page: 1
		};
	},

	computed: {
		...mapState('user', ['loading', 'marks']),
		...mapGetters('user', ['getFormattedMarkDate'])
	},

	methods: {
		...mapActions('user', ['getUserMarks']),

		showMore() {
			if (this.page + 1 <= this.marks.last_page) {
				this.page++;

				this.getUserMarks({ page: this.page }).then(() => {
					this.infiniteMarks.data.push(...this.marks.data);
				});
			}
		},

		loadMoreMarks(isVisible) {
			if (isVisible) {
				this.showMore();
			}
		}
	},

	created() {
		this.getUserMarks({ page: this.page }).then(() => {
			this.infiniteMarks = this.marks;
		});
	}
};
</script>

<style>
.slidedown-enter-active,
.slidedown-leave-active {
	transform-origin: top;
	transition: transform 0.3s ease-in-out;
}

.slidedown-enter-to,
.slidedown-leave-from {
	transform: scaleY(1);
}

.slidedown-enter-from,
.slidedown-leave-to {
	transform: scaleY(0);
}
</style>
