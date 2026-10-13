<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <div v-if="field.value" class="tinymce-detail-content">{{ field.value }}</div>
            <div v-else>No content</div>
        </template>
    </PanelItem>
</template>

<script>
export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
}
</script>
