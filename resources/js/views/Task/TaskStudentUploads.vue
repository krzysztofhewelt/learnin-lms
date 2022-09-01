<template>
    <div class="relative w-full flex flex-col h-fit break-words rounded-lg bg-white shadow-xl">
        <LoadingScreen v-if="loading" />
        <div class="p-12">

            <router-link :to="{name:'TasksView', params:{id:$route.params.id}}" class="block mb-3 no-underline font-bold">
                &lt; &lt; {{ $t('mark.back_to_task') }}
            </router-link>

            <h1 class="block text-xl font-bold">{{ $t('task.student_upload_title') }}</h1>
            <p class="mb-4">
                <b>{{ $t('task.task_name') }}:</b> {{ task.name }}<br>
                <b>{{ $t('task.available_from_label') }}:</b> {{ getFormattedDate(task.available_from) }}<br>
                <b>{{ $t('task.available_to_label') }}:</b> {{ getFormattedDate(task.available_to) || $t('general.not_available') }}<br>
            </p>

            <button class="normal_btn w-fit" @click="downloadAll(task.id)">
                {{ $t('task.download_zip') }}
            </button>

            <router-link :to="{name:'MarksTask', params:{id:$route.params.id}}" class="normal_btn ml-3">
                {{ $t('task.issue_grades') }}
            </router-link>

            <div class="divide-y divide-gray-200 mt-10">
                <div v-for="student in allStudentFilesInTask" :key="student.id">
                    <h1 class="text-xl font-bold mt-4">{{ student.surname }} {{ student.name }}</h1>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-6 mb-4">
                        <div v-if="student.files.length === 0" class="font-bold text-lg underline text-red-500">
                            {{ $t('general.no_files') }}
                        </div>
                        <div v-for="file in student.files" :key="file.file_ID">
                            <button class="block font-bold text-purple-800 no-underline text-left hover:text-purple-500 break-all"
                                    @click="download(file.file_ID)">
                                {{ file.filename_original }} ({{ file.file_size }} {{ file.file_size_unit }})
                            </button>
                            <div class="text-gray-400">
                                <Popper :content="getFormattedDate(file.updated_at)" hover>
                                    <span class="underline decoration-dotted">{{ getRelativeTime(file.updated_at) }}</span>
                                </Popper>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters, mapState} from "vuex"
import LoadingScreen from "../../components/LoadingScreen"
import Popper from "vue3-popper"

export default {
    name: "TaskStudentUploads",
    components: {LoadingScreen, Popper},

    computed: {
        ...mapState('task', ['loading', 'task', 'allStudentFilesInTask']),
        ...mapGetters('task', ['getFormattedDate', 'getRelativeTime'])
    },

    methods: {
        ...mapActions('task', ['getStudentFilesInTask']),

        download(fileId) {
            axios.post('/download/' + fileId, {
                file_type: 'student_upload'
            }, {
                responseType: 'blob'
            })
                .then((res) => {
                    let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/
                    let matches = filenameRegex.exec(res.headers['content-disposition'])
                    let filename

                    if (matches != null && matches[1])
                        filename = matches[1].replace(/['"]/g, "")

                    let fileURL = window.URL.createObjectURL(new Blob([res.data]))
                    let fileLink = document.createElement('a')

                    fileLink.href = fileURL
                    fileLink.setAttribute('download', filename)
                    document.body.appendChild(fileLink)

                    fileLink.click()
                })
        },

        async downloadAll(taskId) {
            this.$store.commit('task/loading', true)

            await axios.get('/tasks/students_uploads/' + taskId + '/zip', {
                responseType: 'blob'
            })
                .then((res) => {
                    let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/
                    let matches = filenameRegex.exec(res.headers['content-disposition'])
                    let filename

                    if (matches != null && matches[1])
                        filename = matches[1].replace(/['"]/g, "")

                    let fileURL = window.URL.createObjectURL(new Blob([res.data]))
                    let fileLink = document.createElement('a')

                    fileLink.href = fileURL
                    fileLink.setAttribute('download', filename)
                    document.body.appendChild(fileLink)

                    fileLink.click()
                })

            this.$store.commit('task/loading', false)
        }
    },

    created() {
        this.getStudentFilesInTask(this.$route.params.id)
    }
}
</script>
