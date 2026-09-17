@php
    use Filament\Support\Facades\FilamentAsset;

    $strings = [
        'empty' => __('cloudcake-components::mermaid.empty'),
        'renderError' => __('cloudcake-components::mermaid.render_error'),
    ];
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
            class="mermaid-field-container"
            x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle(" . \Illuminate\Support\Js::from($getStatePath()) . ")") }},
            strings: @js($strings),
            mermaid: null,
            renderCount: 0,
            themeObserver: null,

            get isDark() {
                return document.documentElement.classList.contains('dark')
            },

            async init() {

                this.mermaid = (await import('https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.esm.min.mjs')).default
                this.applyTheme()

                this.themeObserver = new MutationObserver(() => {
                    this.applyTheme()
                    this.draw()
                })
                this.themeObserver.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class'],
                })

                this.draw()
            },

            destroy() {
                this.themeObserver?.disconnect()
            },

            applyTheme() {
                this.mermaid.initialize({
                    startOnLoad: false,
                    theme: this.isDark ? '{{ $getDarkTheme()->value }}' : '{{ $getTheme()->value }}',
                    htmlLabels: false,
                })
            },

            async draw() {
                const preview = this.$refs.preview
                const definition = this.state

                if (! definition.trim()) {
                    preview.textContent = this.strings.empty

                    return
                }

                const svgId = 'mermaid-svg-' + this.$id('mermaid-field') + '-' + (++this.renderCount)

                try {
                    const { svg } = await this.mermaid.render(svgId, definition)
                    preview.innerHTML = svg.replace(/[ ]*max-width:[ 0-9.]*px;/i, '')
                } catch (error) {
                    preview.textContent = this.strings.renderError + ' ' + error.message
                }

                svgPanZoom('#' + svgId, {
                      viewportSelector: '.mermaid-render'
                    , panEnabled: {{ \Illuminate\Support\Js::from($allowsPanning()) }}
                    , controlIconsEnabled: false
                    , zoomEnabled: {{ \Illuminate\Support\Js::from($allowsZooming()) }}
                    , dblClickZoomEnabled: true
                    , mouseWheelZoomEnabled: true
                    , preventMouseEventsDefault: true
                    , zoomScaleSensitivity: 0.2
                    , minZoom: 0.5
                    , maxZoom: 10
                    , fit: true
                    , contain: true
                    , center: true
                    , refreshRate: 'auto'
                });
            },
        }"
    >
            <div class="mermaid-render" wire:ignore x-ref="preview"></div>
    </div>
</x-dynamic-component>
