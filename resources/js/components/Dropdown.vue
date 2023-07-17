<template>
    <Popper v-bind="$attrs" content="&nbsp;">
        <slot />
        <template #content="props">
            <slot name="content" v-bind="props" />
        </template>
    </Popper>
</template>

<script>
import Popper from 'vue3-popper';

export default {
	name: 'Dropdown',

    components: {
        Popper
    }
};
</script>
