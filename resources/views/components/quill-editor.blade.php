
@props([
    'value' => '',
    'placeholder' => 'Write content...',
    'height' => '400px',
    'toolbar' => 'full'
])

<div
    x-data="quillEditor(@entangle($attributes->wire('model')), @js($placeholder), @js($height), @js($toolbar))"
    x-init="init()"
    wire:ignore.self
    @php
        $model = $attributes->wire('model')->value();
    @endphp
    class="quill-editor-container"
>
    <div x-ref="editorContainer"></div>

    <textarea
        x-ref="hiddenTextarea"
        class="hidden"
        wire:model="{{ $model }}"
    >{{ $value }}</textarea>
</div>

<style>
.quill-editor-container {
    margin-bottom: 1rem;
}
.ql-toolbar.ql-snow {
    border: 1px solid #e5e7eb;
    border-bottom: none;
    border-radius: 0.375rem 0.375rem 0 0;
    background: #f9fafb;
}
.ql-container.ql-snow {
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
}
.ql-editor {
    font-size: 14px;
    line-height: 1.6;
    font-family: 'Inter', sans-serif;
}
.ql-editor.ql-blank::before {
    color: #9ca3af;
    font-style: normal;
    font-family: 'Inter', sans-serif;
}
.dark .ql-toolbar.ql-snow {
    border-color: #4b5563;
    background: #374151;
}
.dark .ql-container.ql-snow {
    border-color: #4b5563;
    background: #1f2937;
}
.dark .ql-editor {
    background: #1f2937;
    color: #f9fafb;
}
.dark .ql-snow .ql-stroke {
    stroke: #d1d5db;
}
.dark .ql-snow .ql-fill {
    fill: #d1d5db;
}
.dark .ql-snow .ql-picker {
    color: #d1d5db;
}
.dark .ql-snow .ql-picker-options {
    background: #374151;
    border-color: #4b5563;
}
</style>
