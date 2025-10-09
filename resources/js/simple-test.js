// Simple test to verify component registration
console.log('TinyMCE test script loading...');

Nova.booting((app, store) => {
    console.log('Registering simple test component...');
    
    app.component('tinymce', {
        props: ['resourceName', 'resourceId', 'field'],
        
        data() {
            return {
                value: this.field.value || '',
            };
        },
        
        mounted() {
            console.log('TinyMCE component mounted!', this.field);
            this.$nextTick(() => {
                this.$emit('field-changed', this.field.attribute, this.value);
            });
        },
        
        watch: {
            value(newValue) {
                this.$emit('field-changed', this.field.attribute, newValue);
            }
        },
        
        methods: {
            handleInput(event) {
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
                    @input="handleInput"
                    :placeholder="field.placeholder"
                    class="w-full form-control form-input form-input-bordered"
                ></textarea>
                <p class="text-sm text-gray-500 mt-1">TinyMCE field loaded (test mode)</p>
            </div>
        `
    });
});
