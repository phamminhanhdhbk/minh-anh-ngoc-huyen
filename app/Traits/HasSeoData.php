<?php

namespace App\Traits;

use App\SeoData;

trait HasSeoData
{
    /**
     * Get the SEO data for this model.
     */
    public function seoData()
    {
        return $this->morphOne(SeoData::class, 'model');
    }

    /**
     * Get or create SEO data
     */
    public function getSeoData()
    {
        return $this->seoData ?: new SeoData([
            'model_type' => get_class($this),
            'model_id' => $this->id
        ]);
    }

    /**
     * Update SEO data
     */
    public function updateSeoData(array $data)
    {
        return SeoData::setForModel($this, $data);
    }

    /**
     * Get meta title with fallback
     */
    public function getMetaTitle()
    {
        $seo = $this->getSeoData();
        return $seo->meta_title ?: $this->getDefaultMetaTitle();
    }

    /**
     * Get meta description with fallback
     */
    public function getMetaDescription()
    {
        $seo = $this->getSeoData();
        return $seo->meta_description ?: $this->getDefaultMetaDescription();
    }

    /**
     * Get meta keywords
     */
    public function getMetaKeywords()
    {
        $seo = $this->getSeoData();
        return $seo->meta_keywords ?: $this->getDefaultMetaKeywords();
    }

    /**
     * Get Open Graph title
     */
    public function getOgTitle()
    {
        $seo = $this->getSeoData();
        return $seo->og_title ?: $this->getMetaTitle();
    }

    /**
     * Get Open Graph description
     */
    public function getOgDescription()
    {
        $seo = $this->getSeoData();
        return $seo->og_description ?: $this->getMetaDescription();
    }

    /**
     * Get Open Graph image
     */
    public function getOgImage()
    {
        $seo = $this->getSeoData();
        return $seo->og_image ?: $this->getDefaultOgImage();
    }

    /**
     * Get schema markup
     */
    public function getSchemaMarkup()
    {
        $seo = $this->getSeoData();
        return $seo->schema_markup ?: $this->generateDefaultSchema();
    }

    /**
     * Default implementations - to be overridden in models
     */
    protected function getDefaultMetaTitle()
    {
        return $this->name ?? $this->title ?? 'Untitled';
    }

    protected function getDefaultMetaDescription()
    {
        return $this->description ?? $this->excerpt ?? '';
    }

    protected function getDefaultMetaKeywords()
    {
        return '';
    }

    protected function getDefaultOgImage()
    {
        return null;
    }

    protected function generateDefaultSchema()
    {
        return null;
    }
}
