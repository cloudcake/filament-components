@php
    use Filament\Support\Facades\FilamentAsset;

    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $rows = $getRows();
    $shouldAutosize = $shouldAutosize();
    $statePath = $getStatePath();

    $initialHeight = max(25, (($rows ?? 2) * 1.5) + 0.75);

    $strings = [
        'verticalView' => __('cloudcake-components::mermaid.vertical_view'),
        'parallelView' => __('cloudcake-components::mermaid.parallel_view'),
        'empty' => __('cloudcake-components::mermaid.empty'),
        'renderError' => __('cloudcake-components::mermaid.render_error'),
    ];
@endphp

@once
    <link
            rel="stylesheet"
            href="{{ FilamentAsset::getStyleHref('mermaid', package: 'cloudcake-components') }}"
    />
    <script src="{{ FilamentAsset::getScriptSrc('svg-pan-zoom', package: 'cloudcake-components') }}"></script>
@endonce

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        class="mermaid-field-container"
        x-data="{
            strings: @js($strings),
            parallel: true,
            mermaid: null,
            textarea: null,
            renderCount: 0,
            debounce: null,
            themeObserver: null,

            get toggleLabel() {
                return this.parallel ? this.strings.verticalView : this.strings.parallelView
            },

            get isDark() {
                return document.documentElement.classList.contains('dark')
            },

            async init() {
                this.textarea = this.$refs.editor.querySelector('textarea')

                this.mermaid = (await import('https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.esm.min.mjs')).default
                this.applyTheme()

                this.textarea?.addEventListener('input', () => {
                    clearTimeout(this.debounce)
                    this.debounce = setTimeout(() => this.draw(), 100)
                })

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
                clearTimeout(this.debounce)
                this.themeObserver?.disconnect()
            },

            applyTheme() {
                this.mermaid.initialize({
                    startOnLoad: true,
                    theme: this.isDark ? '{{ $getDarkTheme() }}' : '{{ $getTheme() }}',
                    htmlLabels: false,
                })
            },

            async draw() {
                const preview = this.$refs.preview
                const definition = this.textarea?.value ?? ''

                if (! definition.trim()) {
                    preview.textContent = this.strings.empty

                    return
                }

                const svgId = 'mermaid-svg-' + this.$id('mermaid-field') + '-' + (++this.renderCount)

                try {
                    const { svg } = await this.mermaid.render(svgId, definition)
                    preview.innerHTML = svg
                } catch (error) {
                    preview.textContent = this.strings.renderError + ' ' + error.message
                }
            },
        }"
    >
        <div class="mermaid-layout-vertical">
            <div class="mermaid-editor-section" x-ref="editor">
                <x-filament::input.wrapper
                    :disabled="$isDisabled"
                    :valid="! $errors->has($statePath)"
                    :attributes="
                        \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                            ->class(['fi-fo-textarea'])
                    "
                >
                    <div wire:ignore.self style="height: {{ $initialHeight }}rem">
                        <textarea
                            x-load
                            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('textarea', 'filament/forms') }}"
                            x-data="textareaFormComponent({
                                        initialHeight: @js($initialHeight),
                                        shouldAutosize: @js($shouldAutosize),
                                        state: $wire.$entangle('{{ $statePath }}'),
                                    })"
                            @if ($shouldAutosize)
                                x-intersect.once="resize()"
                                x-on:resize.window="resize()"
                            @endif
                            x-model="state"
                            {{ $getExtraAlpineAttributeBag() }}
                            {{
                                $getExtraInputAttributeBag()
                                    ->merge([
                                        'autocomplete' => $getAutocomplete(),
                                        'autofocus' => $isAutofocused(),
                                        'cols' => $getCols(),
                                        'disabled' => $isDisabled,
                                        'id' => $getId(),
                                        'maxlength' => (! $isConcealed) ? $getMaxLength() : null,
                                        'minlength' => (! $isConcealed) ? $getMinLength() : null,
                                        'placeholder' => $getPlaceholder(),
                                        'readonly' => $isReadOnly(),
                                        'required' => $isRequired() && (! $isConcealed),
                                        'rows' => $rows,
                                        $applyStateBindingModifiers('wire:model') => $statePath,
                                    ], escape: false)
                            }}
                        ></textarea>
                    </div>
                </x-filament::input.wrapper>
            </div>

            <div class="mermaid-preview-section">
                <div class="mermaid-render" wire:ignore x-ref="preview"></div>
            </div>
        </div>
    </div>
</x-dynamic-component>
