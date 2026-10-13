<template>
    <DefaultField :field="field" :errors="errors">
        <template #field>
            <textarea
                ref="textarea"
                :id="uniqueId"
                :name="field.attribute"
                :value="value"
                @input="handleChange"
                :placeholder="field.placeholder"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
            ></textarea>
            <p v-if="loadError" role="alert">The rich text editor could not load. You can still edit and save the HTML in this text area.</p>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import { markRaw } from 'vue'
import { loadTinyMCE, editorOptions } from '../tinymce'

export default {
    mixins: [FormField, HandlesValidationErrors],

    data() {
        return {
            editor: null,
            editorReady: false,
            disposed: false,
            loadError: false,
            uniqueId: `tinymce_${Math.random().toString(36).slice(2)}`,
        }
    },

    mounted() {
        this.initializeEditor()
    },

    beforeUnmount() {
        this.disposed = true
        this.destroyEditor()
    },

    watch: {
        value(value) {
            if (this.editorReady && this.editor.getContent() !== (value || '')) {
                this.editor.setContent(value || '')
            }
        },
    },

    methods: {
        async initializeEditor() {
            try {
                await this.$nextTick()
                const tiny = await loadTinyMCE(this.field)
                if (this.disposed) return
                const editors = await tiny.init({
                    ...editorOptions(this.field),
                    target: this.$refs.textarea,
                    setup: (editor) => {
                        if (this.disposed) {
                            editor.remove()
                            return
                        }
                        this.editor = markRaw(editor)
                        editor.on('init', () => {
                            if (this.disposed) {
                                editor.remove()
                                return
                            }
                            editor.setContent(this.value || '')
                            this.editorReady = true
                        })
                        editor.on('input change undo redo SetContent blur', () => {
                            if (this.editorReady && !this.disposed) {
                                this.updateValue(editor.getContent())
                            }
                        })
                    },
                })
                if (this.disposed) {
                    editors.forEach(editor => editor.remove())
                }
            } catch (error) {
                if (!this.disposed) {
                    this.destroyEditor()
                    this.loadError = true
                }
            }
        },

        updateValue(value) {
            this.value = value
            this.emitFieldValueChange(this.fieldAttribute, value)
            this.$emit('field-changed')
        },

        handleChange(event) {
            this.updateValue(event.target.value)
        },

        destroyEditor() {
            this.editorReady = false
            if (this.editor) {
                this.editor.remove()
                this.editor = null
            }
        },

        fill(formData) {
            if (this.editorReady) {
                this.value = this.editor.getContent()
            }
            this.fillIfVisible(formData, this.fieldAttribute, this.value || '')
        },
    },
}
</script>
