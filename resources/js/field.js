import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'
import PreviewField from './components/PreviewField'

Nova.booting((app, store) => {
  app.component('index-tinymce', IndexField)
  app.component('detail-tinymce', DetailField)
  app.component('form-tinymce', FormField)
  // app.component('preview-tinymce', PreviewField)
})
