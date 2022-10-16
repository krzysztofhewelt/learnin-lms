<template>
    <Modal v-model="showModal" :title="$t('course.course_participants')" wrapper-class="modal-wrapper" modal-class="scrollable-modal">
        <div class="scrollable-content">
            <div v-for="user in participants"
                 :key="user.id"
                 class="font-bold cursor-pointer py-0.5"
                 @click="$router.push({name:'UsersView', params: {id:user.id}})">

                {{ user.surname }} {{ user.name }}
                <span v-if="user.account_role === 'teacher'" class="bg-red-100 text-red-900 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-200">
                    {{ $t('user.teacher') }}
                </span>

                <span v-if="user.account_role === 'admin'" class="bg-red-100 text-red-900 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-200">
                    {{ $t('user.admin') }}
                </span>
            </div>
        </div>

        <div class="row scrollable-modal-footer">
            <div class="col-sm-12">
                <div class="float-right">
                    <button class="normal_btn" type="button" @click.prevent="showModal = false">{{ $t('general.close') }}</button>
                </div>
            </div>
        </div>
    </Modal>

</template>

<script>
import VueModal from "@kouts/vue-modal"
import '@kouts/vue-modal/dist/vue-modal.css'

export default {
    name: "ParticipantsModal",

    components: {
        'Modal': VueModal
    },

    props: {
        showModal: {
            type: Boolean,
            default: false
        },

        participants: {
            type: Array,
            required: true
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

    .scrollable-modal {
        display: flex;
        flex-direction: column;
        max-height: calc(100% - 50px);
    }
    .scrollable-modal .vm-titlebar {
        flex-shrink: 0;
    }
    .scrollable-modal .vm-content {
        padding: 0;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    .scrollable-modal .vm-content .scrollable-content {
        position: relative;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 10px 15px 10px 15px;
        flex-grow: 1;
    }
    .scrollable-modal .scrollable-modal-footer {
        padding: 15px 0px 15px 0px;
        border-top: 1px solid #e5e5e5;
        margin-left: 0;
        margin-right: 0;
    }
</style>
