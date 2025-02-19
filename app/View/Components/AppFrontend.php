<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppFrontend extends Component
{
    public $title;
    public $metaDescription;
    public $ogTitle;
    public $ogDescription;
    public $ogUrl;
    public $ogType;
    public $ogImage;
    public $canonicalUrl;

    public function __construct(
        $title = null,
        $metaDescription = null,
        $ogTitle = null,
        $ogDescription = null,
        $ogUrl = null,
        $ogType = 'website',
        $ogImage = null,
        $canonicalUrl = null
    ) {
        $this->title = $title;
        $this->metaDescription = $metaDescription;
        $this->ogTitle = $ogTitle;
        $this->ogDescription = $ogDescription;
        $this->ogUrl = $ogUrl;
        $this->ogType = $ogType;
        $this->ogImage = $ogImage;
        $this->canonicalUrl = $canonicalUrl;
    }

    public function render()
    {
        return view('layouts.app-frontend');
    }
} 