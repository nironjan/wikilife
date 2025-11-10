<!-- This component handles meta tags via Livewire and Meta facade -->
@script
<script>
    // Update meta tags on client side when component updates
    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(() => {
            // Update document title
            if (component.$wire.get('title')) {
                document.title = component.$wire.get('title');
            }

            // Update meta description
            let metaDesc = document.querySelector('meta[name="description"]');
            if (metaDesc && component.$wire.get('description')) {
                metaDesc.setAttribute('content', component.$wire.get('description'));
            }

            // Update canonical
            let canonical = document.querySelector('link[rel="canonical"]');
            if (canonical && component.$wire.get('canonical')) {
                canonical.setAttribute('href', component.$wire.get('canonical'));
            }

            // Update Open Graph tags
            let ogTitle = document.querySelector('meta[property="og:title"]');
            if (ogTitle && component.$wire.get('ogTitle')) {
                ogTitle.setAttribute('content', component.$wire.get('ogTitle'));
            }

            let ogDesc = document.querySelector('meta[property="og:description"]');
            if (ogDesc && component.$wire.get('ogDescription')) {
                ogDesc.setAttribute('content', component.$wire.get('ogDescription'));
            }

            let ogUrl = document.querySelector('meta[property="og:url"]');
            if (ogUrl && component.$wire.get('ogUrl')) {
                ogUrl.setAttribute('content', component.$wire.get('ogUrl'));
            }

            let ogImage = document.querySelector('meta[property="og:image"]');
            if (ogImage && component.$wire.get('ogImage')) {
                ogImage.setAttribute('content', component.$wire.get('ogImage'));
            }

            // Update Twitter tags
            let twitterTitle = document.querySelector('meta[name="twitter:title"]');
            if (twitterTitle && component.$wire.get('twitterTitle')) {
                twitterTitle.setAttribute('content', component.$wire.get('twitterTitle'));
            }

            let twitterDesc = document.querySelector('meta[name="twitter:description"]');
            if (twitterDesc && component.$wire.get('twitterDescription')) {
                twitterDesc.setAttribute('content', component.$wire.get('twitterDescription'));
            }

            let twitterImage = document.querySelector('meta[name="twitter:image"]');
            if (twitterImage && component.$wire.get('twitterImage')) {
                twitterImage.setAttribute('content', component.$wire.get('twitterImage'));
            }
        });
    });
</script>
@endscript

<!-- Output structured data as JSON-LD -->
@if(!empty($structuredData))
    @foreach($structuredData as $data)
        <script type="application/ld+json">
            {!! is_array($data) ? json_encode($data) : $data !!}
        </script>
    @endforeach
@endif
