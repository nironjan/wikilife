<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use F9Web\Meta\Meta;

class MetaTags extends Component
{
    public $title;
    public $description;
    public $keywords;
    public $canonical;
    public $ogTitle;
    public $ogDescription;
    public $ogType = 'website';
    public $ogUrl;
    public $ogImage;
    public $ogSiteName;
    public $twitterCard = 'summary_large_image';
    public $twitterTitle;
    public $twitterDescription;
    public $twitterImage;
    public $structuredData = [];

    public function mount(
        $title = null,
        $description = null,
        $keywords = null,
        $canonical = null,
        $ogTitle = null,
        $ogDescription = null,
        $ogType = 'website',
        $ogUrl = null,
        $ogImage = null,
        $ogSiteName = null,
        $twitterCard = 'summary_large_image',
        $twitterTitle = null,
        $twitterDescription = null,
        $twitterImage = null,
        $structuredData = []
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->canonical = $canonical;
        $this->ogTitle = $ogTitle ?: $title;
        $this->ogDescription = $ogDescription ?: $description;
        $this->ogType = $ogType;
        $this->ogUrl = $ogUrl ?: $canonical;
        $this->ogImage = $ogImage;
        $this->ogSiteName = $ogSiteName ?: config('app.name', 'WikiLife');
        $this->twitterCard = $twitterCard;
        $this->twitterTitle = $twitterTitle ?: $title;
        $this->twitterDescription = $twitterDescription ?: $description;
        $this->twitterImage = $twitterImage ?: $ogImage;
        $this->structuredData = $structuredData;

        $this->setMetaTags();
    }

    public function setMetaTags()
    {
        if ($this->title) {
            Meta::set('title', $this->title);
        }

        if ($this->description) {
            Meta::set('description', $this->description);
        }

        if ($this->keywords) {
            Meta::set('keywords', $this->keywords);
        }

        if ($this->canonical) {
            Meta::set('canonical', $this->canonical);
        }

        // Open Graph
        if ($this->ogTitle) {
            Meta::set('og:title', $this->ogTitle);
        }

        if ($this->ogDescription) {
            Meta::set('og:description', $this->ogDescription);
        }

        Meta::set('og:type', $this->ogType);

        if ($this->ogUrl) {
            Meta::set('og:url', $this->ogUrl);
        }

        if ($this->ogImage) {
            Meta::set('og:image', $this->ogImage);
            Meta::set('og:image:width', 1200);
            Meta::set('og:image:height', 630);
        }

        if ($this->ogSiteName) {
            Meta::set('og:site_name', $this->ogSiteName);
        }

        // Twitter Card
        Meta::set('twitter:card', $this->twitterCard);

        if ($this->twitterTitle) {
            Meta::set('twitter:title', $this->twitterTitle);
        }

        if ($this->twitterDescription) {
            Meta::set('twitter:description', $this->twitterDescription);
        }

        if ($this->twitterImage) {
            Meta::set('twitter:image', $this->twitterImage);
        }
    }

    public function updateMeta(
        $title = null,
        $description = null,
        $keywords = null,
        $canonical = null,
        $ogTitle = null,
        $ogDescription = null,
        $ogImage = null,
        $structuredData = []
    ) {
        $this->title = $title ?: $this->title;
        $this->description = $description ?: $this->description;
        $this->keywords = $keywords ?: $this->keywords;
        $this->canonical = $canonical ?: $this->canonical;
        $this->ogTitle = $ogTitle ?: $this->ogTitle;
        $this->ogDescription = $ogDescription ?: $this->ogDescription;
        $this->ogImage = $ogImage ?: $this->ogImage;
        $this->structuredData = $structuredData ?: $this->structuredData;

        $this->setMetaTags();
    }

    public function render()
    {
        return view('livewire.partials.meta-tags');
    }
}
