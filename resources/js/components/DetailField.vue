<template>
    <div v-if="field.value" v-html="field.value"></div>
    <div v-else class="text-gray-500 italic">No content</div>
</template>

<script>
export default {
    props: ['resourceName', 'resourceId', 'field'],
}
</script>