<template>
    <section class="flex items-center h-full p-16 dark:bg-gray-900 dark:text-gray-100">
        <div class="container flex flex-col items-center justify-center px-5 mx-auto my-8 text-center">
            <h2 class="mb-8 font-extrabold text-9xl dark:text-gray-600">
                404
            </h2>
            <p class="text-2xl font-semibold md:text-3xl">{{ $t('404.not_found_header') }}</p>
            <a href="/" class="px-8 py-3 font-semibold no-underline rounded dark:bg-violet-400 dark:text-gray-900">{{ $t('404.back_to_homepage') }}</a>
        </div>
    </section>
</template>

<script>
export default {
    name: "Error404"
}
</script>
