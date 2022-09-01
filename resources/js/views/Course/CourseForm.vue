<template>
    <div class="flex relative w-full h-fit flex-col break-words rounded-lg bg-white shadow-xl">
        <LoadingScreen v-if="loading" />
        <form @submit.prevent="handleSubmit">
            <div class="p-6">
                <h1 class="text-xl font-bold mb-4">
                    {{ (this.$route.name === 'CoursesEdit') ? $t('course.editing_course') : $t('course.creating_course') }}
                </h1>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <div class="col-span-4">
                        <BaseInputGroup
                            id="name"
                            :label="$t('course.course_name')"
                            type="text"
                            :required="true"
                            v-model="course.name"
                            :validation-errors="validationErrors.name"
                        />
                    </div>
                    <div class="col-span-4">
                        <BaseTextarea
                            id="description"
                            :label="$t('course.course_description')"
                            v-model="course.description"
                            :validation-errors="validationErrors.description"
                        />
                    </div>
                    <div class="col-span-4 lg:col-span-2">
                        <BaseInputGroup
                            id="available_from"
                            type="datetime-local"
                            :label="$t('course.available_from_label')"
                            :required="true"
                            v-model="availableFrom"
                            :validation-errors="validationErrors.available_from"
                            />
                    </div>
                    <div class="col-span-4 lg:col-span-2">
                        <BaseInputGroup
                            id="available_to"
                            type="datetime-local"
                            :label="$t('course.available_to_label')"
                            v-model="availableTo"
                            :validation-errors="validationErrors.available_to"
                        />
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-100 text-right rounded-b-lg">
                <ButtonSubmit :loading="loading" />
            </div>
        </form>
    </div>
</template>

<script>
import BaseInputGroup from "../../components/BaseInputGroup"
import BaseTextarea from "../../components/BaseTextarea"
import ButtonSubmit from "../../components/ButtonSubmit"
import LoadingScreen from "../../components/LoadingScreen"
import {mapActions, mapGetters, mapMutations, mapState} from "vuex"
import Multiselect from "vue-multiselect"
import {useToast} from "vue-toastification"
import router from "../../router";

export default {
    name: "CourseForm",
    components: {Multiselect, LoadingScreen, ButtonSubmit, BaseTextarea, BaseInputGroup},

    computed: {
        ...mapState('course', ['course', 'loading', 'validationErrors']),
        ...mapGetters('course', ['getISODate']),

        availableFrom: {
            set: function(val) {
                this.course.available_from = val
            },

            get: function() {
                return this.course.available_from &&  this.getISODate(this.course.available_from)
            }
        },

        availableTo: {
            set: function(val) {
                this.course.available_to = val
            },

            get: function() {
                return this.course.available_to && this.getISODate(this.course.available_to)
            }
        }
    },

    methods: {
        ...mapActions('course', ['getCourseDetails', 'createOrEditCourse']),
        ...mapMutations('course', ['resetCourse']),

        handleSubmit() {
            const toast = useToast()

            this.course.available_from = this.availableFrom
            this.course.available_to = this.availableTo

            this.createOrEditCourse(this.course)
                .then(
                    (response) => {
                        toast.success(this.$t('general.saved_successfully'))
                        router.push({name:'CoursesView', params:{id: response.data.course_ID}})
                    }
                )
                .catch((error) => {
                    toast.error(this.$t('general.validation_errors'))
                    throw error
                })
        }
    },

    created() {
        if(this.$route.params.id)
            this.getCourseDetails(this.$route.params.id)
        else
            this.resetCourse()
    }
}
</script>
