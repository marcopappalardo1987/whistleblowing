<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppFrontendWhistleblowing extends Component
{
    public $title;
    public $metaDescription;
    public $ogTitle;
    public $ogDescription;
    public $ogImage;
    public $ogType;
    public $canonicalUrl;

    public function __construct(
        $title = null,
        $metaDescription = null,
        $ogTitle = null,
        $ogDescription = null,
        $ogImage = null,
        $ogType = 'website',
        $canonicalUrl = null
    ) {
        $this->title = $title;
        $this->metaDescription = $metaDescription;
        $this->ogTitle = $ogTitle;
        $this->ogDescription = $ogDescription;
        $this->ogImage = $ogImage;
        $this->ogType = $ogType;
        $this->canonicalUrl = $canonicalUrl;
    }

    public function render()
    {
        return view('layouts.app-frontend-whistleblowing-page');
    }
} 