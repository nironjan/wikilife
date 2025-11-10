@props([
    'url' => url()->current(),
    'title' => '',
    'description' => '',
    'image' => '',
    'size' => 'sm', // sm, md, lg
    'layout' => 'horizontal', // horizontal, vertical
    'showLabels' => false,
])

@php
    $shareTitle = $title ?: config('app.name');
    $shareText = $description ?: $title;

    // Social share URLs
    $shareUrls = [
        'twitter' => 'https://twitter.com/intent/tweet?' . http_build_query([
            'text' => $shareTitle,
            'url' => $url,
            'via' => config('app.name')
        ]),
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?' . http_build_query([
            'u' => $url,
            'quote' => $shareText
        ]),
        'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?' . http_build_query([
            'url' => $url
        ]),
        'whatsapp' => 'https://wa.me/?' . http_build_query([
            'text' => $shareTitle . ' ' . $url
        ]),
        'telegram' => 'https://t.me/share/url?' . http_build_query([
            'url' => $url,
            'text' => $shareTitle
        ]),
        'email' => 'mailto:?' . http_build_query([
            'subject' => $shareTitle,
            'body' => $shareText . '\n\n' . $url
        ])
    ];

    // Size classes
    $sizeClasses = [
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12'
    ];

    // Layout classes
    $layoutClasses = [
        'horizontal' => 'flex space-x-2',
        'vertical' => 'flex flex-col space-y-2'
    ];

    // Social media colors
    $socialColors = [
        'twitter' => 'bg-blue-400 hover:bg-blue-500',
        'facebook' => 'bg-blue-600 hover:bg-blue-700',
        'linkedin' => 'bg-blue-800 hover:bg-blue-900',
        'whatsapp' => 'bg-green-500 hover:bg-green-600',
        'telegram' => 'bg-blue-500 hover:bg-blue-600',
        'email' => 'bg-gray-600 hover:bg-gray-700'
    ];

    // Social media icons
    $socialIcons = [
        'twitter' => '<path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>',
        'facebook' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
        'linkedin' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>',
        'whatsapp' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.019 0C5.495 0 .146 5.334.087 11.852c-.06 6.5 5.235 11.846 11.765 11.846a11.817 11.817 0 006.582-1.996l4.203 1.372-1.38-4.197a11.8 11.8 0 001.679-6.521c0-3.174-1.24-6.159-3.492-8.41"/>',
        'telegram' => '<path d="M12 0C5.374 0 0 5.373 0 12s5.374 12 12 12 12-5.373 12-12S18.626 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.14.141-.259.259-.374.261l.213-3.053 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.136-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>',
        'email' => '<path d="M12 12.713l-11.985-9.713h23.97l-11.985 9.713zm0 2.574l-12-9.725v15.438h24v-15.438l-12 9.725z"/>'
    ];

    // Social media labels
    $socialLabels = [
        'twitter' => 'Share on Twitter',
        'facebook' => 'Share on Facebook',
        'linkedin' => 'Share on LinkedIn',
        'whatsapp' => 'Share on WhatsApp',
        'telegram' => 'Share on Telegram',
        'email' => 'Share via Email'
    ];
@endphp

<div class="share-buttons {{ $layoutClasses[$layout] }}">
    @foreach(['twitter', 'facebook', 'linkedin', 'whatsapp', 'telegram', 'email'] as $platform)
        <a href="{{ $shareUrls[$platform] }}"
           target="_blank"
           rel="noopener noreferrer"
           class="flex items-center justify-center {{ $sizeClasses[$size] }} {{ $socialColors[$platform] }} text-white rounded-lg hover:shadow-lg transition-all duration-200 transform hover:scale-105"
           aria-label="{{ $socialLabels[$platform] }}"
           title="{{ $socialLabels[$platform] }}">
            <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 24 24">
                {!! $socialIcons[$platform] !!}
            </svg>
            @if($showLabels)
                <span class="ml-2 text-xs font-medium">{{ ucfirst($platform) }}</span>
            @endif
        </a>
    @endforeach

    <!-- Copy link button -->
    <button type="button"
            onclick="copyToClipboard('{{ $url }}')"
            class="flex items-center justify-center {{ $sizeClasses[$size] }} bg-purple-600 hover:bg-purple-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 transform hover:scale-105"
            aria-label="Copy link"
            title="Copy link">
        <svg class="w-1/2 h-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        @if($showLabels)
            <span class="ml-2 text-xs font-medium">Copy</span>
        @endif
    </button>
</div>

<!-- Copy to clipboard script -->
<!-- Simple Copy to clipboard script -->
<script>
function copyToClipboard(text) {
    // Simple fallback-friendly approach
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        document.execCommand('copy');
        showCopySuccess(event);
    } catch (err) {
        console.error('Failed to copy: ', err);
        // Try modern API as fallback
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showCopySuccess(event);
            }).catch(err => {
                console.error('Clipboard API failed too: ', err);
                alert('Failed to copy link. Please copy manually: ' + text);
            });
        } else {
            alert('Failed to copy link. Please copy manually: ' + text);
        }
    } finally {
        document.body.removeChild(textArea);
    }
}

function showCopySuccess(event) {
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;

    button.innerHTML = `
        <svg class="w-1/2 h-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        ${button.querySelector('span') ? '<span class="ml-2 text-xs font-medium">Copied!</span>' : ''}
    `;
    button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
    button.classList.add('bg-green-600');

    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('bg-green-600');
        button.classList.add('bg-purple-600', 'hover:bg-purple-700');
    }, 2000);
}
</script>
