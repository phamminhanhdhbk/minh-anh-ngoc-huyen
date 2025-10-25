<?php

if (!function_exists('setting')) {
    /**
     * Get site setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\SiteSetting::get($key, $default);
    }
}

if (!function_exists('sanitizeHtml')) {
    /**
     * Sanitize HTML content from CKEditor
     * Allow only safe tags
     *
     * @param string $html
     * @return string
     */
    function sanitizeHtml($html)
    {
        $allowed = '<h1><h2><h3><h4><h5><h6><p><br><strong><b><em><i><u><s><a><img><figure><figcaption><ul><ol><li><blockquote><table><tr><td><th><thead><tbody><tfoot><div><span><iframe>';
        
        return strip_tags($html, $allowed);
    }
}
