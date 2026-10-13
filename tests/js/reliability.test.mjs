import test from 'node:test'
import assert from 'node:assert/strict'
import { readFileSync } from 'node:fs'
import vm from 'node:vm'

const helperSource = readFileSync(new URL('../../resources/js/tinymce.js', import.meta.url), 'utf8')
const { scriptUrl, editorOptions, loadTinyMCE } = await import(`data:text/javascript;base64,${Buffer.from(helperSource).toString('base64')}`)
const componentSource = readFileSync(new URL('../../resources/js/components/FormField.vue', import.meta.url), 'utf8')
    .split('<script>')[1].split('</script>')[0].replace(/^import .*$/gm, '').replace('export default', 'component =')

function instance(loader = async () => tiny) {
    const context = { markRaw: value => value, FormField: {}, HandlesValidationErrors: {}, loadTinyMCE: loader, editorOptions }
    vm.runInNewContext(componentSource, context)
    const component = context.component
    const field = { attribute: 'content', value: '<p>Saved content</p>' }
    const result = { ...component.data(), field, value: field.value, fieldAttribute: 'content',
        $refs: { textarea: {} }, $nextTick: async () => {},
        emitFieldValueChange() {}, $emit() {}, fillIfVisible(form, key, value) { form.append(key, value) },
    }
    for (const [key, method] of Object.entries(component.methods)) result[key] = method.bind(result)
    result.unmount = () => component.beforeUnmount.call(result)
    result.watchValue = value => component.watch.value.call(result, value)
    return result
}
function editor() {
    const events = new Map()
    return { content: '', removed: 0,
        on(names, callback) { for (const name of names.split(' ')) events.set(name, callback) },
        emit(name) { events.get(name)?.() },
        getContent() { return this.content }, setContent(value) { this.content = value; this.emit('SetContent') },
        remove() { this.removed++ },
    }
}
let tiny

test('resolves configured local and CDN paths and preserves false options', () => {
    assert.equal(scriptUrl({}), '/tinymce/js/tinymce/tinymce.min.js')
    assert.equal(scriptUrl({ base_url: '/custom/', script_path: '/assets/tinymce.min.js' }), '/custom/assets/tinymce.min.js')
    assert.equal(scriptUrl({ script_path: 'https://static.example/editor.js' }), 'https://static.example/editor.js')
    assert.equal(scriptUrl({ source: 'cdn', api_key: 'my-key', version: '8' }), 'https://cdn.tiny.cloud/1/my-key/tinymce/8/tinymce.min.js')
    const config = editorOptions({ paste_data_images: false, menubar: false, invalid_elements: 'script', name: 'Nova label' })
    assert.equal(config.paste_data_images, false)
    assert.equal(config.menubar, false)
    assert.equal(config.invalid_elements, 'script')
    assert.equal(config.base_url, '/tinymce/js/tinymce')
    assert.equal(config.name, undefined)
})

test('concurrent fields share script loading; errors reject and allow retry', async () => {
    const scripts = []
    globalThis.window = {}
    globalThis.document = { createElement: () => ({ remove() { this.removed = true } }), head: { appendChild: script => scripts.push(script) } }
    const first = loadTinyMCE({ base_url: '/shared' })
    assert.equal(first, loadTinyMCE({ base_url: '/shared' }))
    assert.equal(scripts.length, 1)
    scripts[0].onerror()
    await assert.rejects(first, /could not be loaded/)
    assert.equal(scripts[0].removed, true)
    const retry = loadTinyMCE({ base_url: '/shared' })
    window.tinymce = { init() {} }
    scripts[1].onload()
    assert.equal(await retry, window.tinymce)
})

test('failed initialization keeps editable HTML and serializes fallback content', async () => {
    const field = instance(async () => { throw new Error('offline') })
    await field.initializeEditor()
    assert.equal(field.loadError, true)
    field.handleChange({ target: { value: '<p>Fallback edit</p>' } })
    const form = new FormData()
    field.fill(form)
    assert.equal(form.get('content'), '<p>Fallback edit</p>')
})

test('save reads current content before events; validation and external changes retain content', async () => {
    const active = editor()
    tiny = { async init(config) { config.setup(active); active.emit('init'); return [active] } }
    const field = instance()
    await field.initializeEditor()
    assert.equal(active.content, '<p>Saved content</p>')
    active.content = '<ul><li>Last keystroke</li></ul>'
    const form = new FormData()
    field.fill(form)
    assert.equal(form.get('content'), active.content)
    field.errors = { content: ['Required'] }
    assert.equal(field.value, active.content)
    active.content = '<p>Undo</p>'; active.emit('undo')
    assert.equal(field.value, '<p>Undo</p>')
    field.value = ''; field.watchValue('')
    assert.equal(active.content, '')
    field.unmount()
    assert.equal(active.removed, 1)
})

test('navigation during script loading prevents late initialization', async () => {
    let resolve
    let calls = 0
    const field = instance(() => new Promise(done => { resolve = done }))
    const pending = field.initializeEditor()
    await new Promise(setImmediate)
    field.unmount()
    resolve({ init() { calls++ } })
    await pending
    assert.equal(calls, 0)
})

test('navigation during editor initialization removes the pending instance', async () => {
    const active = editor()
    let finish
    const field = instance(async () => ({ init(config) { config.setup(active); return new Promise(resolve => { finish = resolve }) } }))
    const pending = field.initializeEditor()
    await new Promise(setImmediate)
    field.unmount()
    assert.equal(active.removed, 1)
    finish([active])
    await pending
    assert.equal(active.removed, 2)
    assert.equal(field.editor, null)
})
