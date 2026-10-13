const pendingScripts = new Map()

export function scriptUrl(field) {
    const base = (field.base_url || (field.source === 'cdn' ? 'https://cdn.tiny.cloud/1' : '/tinymce')).replace(/\/$/, '')
    if (field.source === 'cdn') {
        return `${base}/${encodeURIComponent(field.api_key || 'no-api-key')}/tinymce/${encodeURIComponent(field.version || '7')}/tinymce.min.js`
    }
    const path = field.script_path || '/js/tinymce/tinymce.min.js'
    return /^(?:https?:)?\/\//.test(path) ? path : `${base}/${path.replace(/^\//, '')}`
}

export function loadTinyMCE(field) {
    if (window.tinymce) return Promise.resolve(window.tinymce)
    const url = scriptUrl(field)
    if (pendingScripts.has(url)) return pendingScripts.get(url)

    const promise = new Promise((resolve, reject) => {
        const script = document.createElement('script')
        let timer
        const finish = (error) => {
            clearTimeout(timer)
            script.onload = script.onerror = null
            if (error) {
                script.remove()
                reject(error)
            } else {
                resolve(window.tinymce)
            }
        }
        script.src = url
        script.onload = () => finish(window.tinymce ? null : new Error('TinyMCE script did not expose an editor'))
        script.onerror = () => finish(new Error('TinyMCE script could not be loaded'))
        timer = setTimeout(() => finish(new Error('TinyMCE script load timed out')), 15000)
        document.head.appendChild(script)
    })
    pendingScripts.set(url, promise)
    promise.catch(() => pendingScripts.delete(url))
    return promise
}

export function editorOptions(field) {
    // Nova field metadata must not be forwarded as TinyMCE options.
    const names = [
        'license_key', 'height', 'toolbar', 'plugins', 'menubar', 'statusbar',
        'branding', 'resize', 'content_css', 'body_class', 'placeholder',
        'paste_as_text', 'paste_data_images', 'relative_urls', 'remove_script_host',
        'convert_urls', 'valid_elements', 'invalid_elements', 'extended_valid_elements',
        'custom_elements', 'verify_html', 'cleanup', 'cleanup_on_startup',
        'paste_auto_cleanup_on_paste', 'paste_remove_styles_if_webkit',
        'paste_merge_formats', 'paste_convert_word_fake_lists', 'paste_webkit_styles',
        'paste_retain_style_properties', 'paste_remove_empty_paragraphs',
        'paste_remove_spans', 'paste_remove_styles', 'language', 'language_url',
        'skin', 'skin_url', 'browser_spellcheck', 'toolbar_mode', 'content_style',
    ]
    const options = {
        license_key: 'gpl', height: 400, plugins: 'lists link image code table',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image | code',
        menubar: true, statusbar: true, branding: false, resize: true,
        paste_as_text: false, paste_data_images: true,
        relative_urls: false, remove_script_host: false, convert_urls: true,
    }
    for (const name of names) {
        if (field[name] !== undefined && field[name] !== null) options[name] = field[name]
    }
    // base_url in PHP identifies the distribution root; TinyMCE needs the script directory.
    options.base_url = scriptUrl(field).slice(0, scriptUrl(field).lastIndexOf('/'))
    options.suffix = '.min'
    return options
}
