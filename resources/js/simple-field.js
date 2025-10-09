// Simple TinyMCE field implementation
Nova.booting((app, store) => {
    // Register the field component
    app.component('form-tinymce', {
        props: ['resourceName', 'resourceId', 'field'],
        
        data() {
            return {
                value: this.field.value || '',
                editor: null,
            };
        },
        
        computed: {
            hasError() {
                return this.field.errors && this.field.errors.length > 0;
            }
        },
        
        mounted() {
            this.initializeEditor();
        },
        
        beforeUnmount() {
            this.destroyEditor();
        },
        
        methods: {
            initializeEditor() {
                // Load TinyMCE dynamically
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
                    script.src = 'https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js';
                    script.onload = resolve;
                    document.head.appendChild(script);
                });
            },
            
            initEditor() {
                const config = {
                    selector: `#${this.field.attribute}`,
                    height: this.field.height || 400,
                    toolbar: this.field.toolbar || 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat',
                    plugins: this.field.plugins || 'lists link image paste',
                    menubar: this.field.menubar !== false,
                    statusbar: this.field.statusbar !== false,
                    branding: this.field.branding !== false,
                    resize: this.field.resize !== false,
                    content_css: this.field.content_css,
                    body_class: this.field.body_class,
                    placeholder: this.field.placeholder,
                    init_instance_callback: (editor) => {
                        this.editor = editor;
                        editor.on('change keyup', () => {
                            this.value = editor.getContent();
                            this.$emit('field-changed', this.field.attribute, this.value);
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
                this.$emit('field-changed', this.field.attribute, this.value);
            }
        },
        
        template: `
            <div>
                <textarea
                    :id="field.attribute"
                    :name="field.attribute"
                    :value="value"
                    @input="handleChange"
                    :placeholder="field.placeholder"
                    class="w-full form-control form-input form-input-bordered"
                    :class="{ 'border-danger': hasError }"
                ></textarea>
            </div>
        `
    });
});
