<template>
    {{ getFormattedDate }}
</template>

<script>
import dayjs from "dayjs";

export default {
    name: "Date",

    props: {
        value: {
            type: [String, null],
            required: true,
        },
    },

    computed: {
        getFormattedDate() {
            return this.value ? dayjs(this.value).format("L LTS") : "";
        },
    },
};
</script>
