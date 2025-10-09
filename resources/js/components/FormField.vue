<template>
    <DefaultField :field="field" :errors="errors">
        <template #field>
            <textarea
                :id="uniqueId"
                :name="field.attribute"
                :value="value"
                @input="handleChange"
                :placeholder="field.placeholder"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
            ></textarea>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    data() {
        return {
            editor: null,
            uniqueId: null,
        };
    },

    mounted() {
        // Generate unique ID for this editor instance
        this.uniqueId = `${this.field.attribute}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
        this.initializeEditor();
    },

    beforeUnmount() {
        this.destroyEditor();
    },

    methods: {
        initializeEditor() {
            if (typeof tinymce === 'undefined') {
                this.loadTinyMCE().then(() => {
                    this.initEditor();
                });
            } else {
                this.initEditor();
            }
        },
        
        loadTinyMCE() {
            return new Promise((resolve) => {
                const script = document.createElement('script');
                script.src = '/tinymce/js/tinymce/tinymce.min.js';
                script.onload = resolve;
                document.head.appendChild(script);
            });
        },
        
        initEditor() {
            // Initialize the editor directly
            this.initSingleEditor();
        },

        initSingleEditor() {
            const config = {
                selector: `#${this.uniqueId}`,
                license_key: this.field.license_key || 'gpl',
                height: this.field.height || 400,
                toolbar: this.field.toolbar || 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code | fullscreen',
                plugins: this.field.plugins || 'lists link image paste code table fullscreen wordcount',
                menubar: this.field.menubar !== false,
                statusbar: this.field.statusbar !== false,
                branding: this.field.branding !== false,
                resize: this.field.resize !== false,
                content_css: this.field.content_css,
                body_class: this.field.body_class,
                placeholder: this.field.placeholder,
                paste_as_text: false,
                paste_data_images: true,
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                // Unique instance settings for multiple editors
                instance_id: this.uniqueId,
                init_instance_callback: (editor) => {
                    this.editor = editor;
                    editor.on('change keyup paste', () => {
                        this.value = editor.getContent();
                    });
                },
                setup: (editor) => {
                    editor.on('init', () => {
                        if (this.value) {
                            editor.setContent(this.value);
                        }
                    });
                }
            };
            
            // Initialize the editor
            tinymce.init(config);
        },
        
        destroyEditor() {
            if (this.editor) {
                tinymce.remove(`#${this.uniqueId}`);
                this.editor = null;
            }
        },

        handleChange(event) {
            this.value = event.target.value;
        },

        fill(formData) {
            this.fillIfVisible(formData, this.fieldAttribute, this.value || '')
        }
    }
};
</script>
