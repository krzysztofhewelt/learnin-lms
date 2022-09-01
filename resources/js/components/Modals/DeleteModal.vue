<template>
    <Modal v-model="showModal" :title="$t('general.confirm_delete')" wrapper-class="modal-wrapper">
        <div class="flex m-2">
            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <Warning class="w-8 h-8 text-red-600"/>
            </div>
            <p class="ml-4">
                {{ $t('general.are_you_sure_delete') }}
            </p>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="float-right">
                    <button class="manage_btn" type="button" @click.prevent="showModal = false">{{ $t('general.cancel') }}</button>
                    <button class="delete_btn ml-3" type="button" @click.prevent="deleteResource">{{ $t('general.delete') }}</button>
                </div>
            </div>
        </div>
    </Modal>

</template>

<script>
import VueModal from "@kouts/vue-modal"
import '@kouts/vue-modal/dist/vue-modal.css'
import Warning from "../Icons/Warning"
import {mapActions} from "vuex"
import {useToast} from "vue-toastification";

export default {
    name: "DeleteModal",

    components: {
        Warning,
        'Modal': VueModal
    },

    props: {
        showModal: {
            type: Boolean,
            default: false
        },

        deletedResource: {
            required: true,
            validator: function(obj) {
                return (obj.id && (Number(obj.id) > 0 || obj.id === -1)
                    && obj.type && ['none', 'course', 'courseFile', 'category', 'task', 'taskRef', 'studentFile', 'user'].includes(obj.type));
            }
        }
    },

    methods: {
        ...mapActions('user', ['deleteUser']),
        ...mapActions('course', ['deleteCourse', 'deleteCourseFile', 'deleteCourseCategory']),
        ...mapActions('task', ['deleteTask', 'deleteTaskRefFile', 'deleteStudentFile']),

        deleteResource() {
            switch(this.deletedResource.type) {
                case 'course':
                    this.deleteCourse(this.deletedResource.id)
                    break
                case 'courseFile':
                    this.deleteCourseFile(this.deletedResource.id)
                    break
                case 'category':
                    this.deleteCourseCategory(this.deletedResource.id)
                    break
                case 'task':
                    this.deleteTask(this.deletedResource.id)
                    break
                case 'taskRef':
                    this.deleteTaskRefFile(this.deletedResource.id)
                    break
                case 'studentFile':
                    this.deleteStudentFile(this.deletedResource.id)
                    break
                case 'user':
                    this.deleteUser(this.deletedResource.id)
                    break
            }

            const toast = useToast()
            toast.success(this.$t('general.removed_successfully'))

            this.$emit('closing')
        }
    }
}
</script>

<style>
    .modal-wrapper {
        display: flex;
        align-items: center;
    }
    .modal-wrapper .vm {
        top: auto;
    }
</style>
