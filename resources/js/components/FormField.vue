<template>
    <DefaultField :field="field" :errors="errors">
        <template #field>
            <textarea
                :id="field.attribute"
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
        };
    },

    mounted() {
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
            const config = {
                selector: `#${this.field.attribute}`,
                license_key: 'gpl',
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
            
            tinymce.init(config);
        },
        
        destroyEditor() {
            if (this.editor) {
                tinymce.remove(`#${this.field.attribute}`);
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
