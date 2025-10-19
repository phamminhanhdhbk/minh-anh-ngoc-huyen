<?php

if (!function_exists('renderMenu')) {
    /**
     * Render menu by slug or location
     *
     * @param string $identifier Menu slug or location
     * @param string $type 'slug' or 'location'
     * @param string $cssClass CSS class for menu container
     * @return string
     */
    function renderMenu($identifier, $type = 'slug', $cssClass = 'navbar-nav')
    {
        $menu = null;

        if ($type === 'slug') {
            $menu = \App\Menu::where('slug', $identifier)
                ->where('is_active', true)
                ->with(['items' => function($query) {
                    $query->whereNull('parent_id')
                        ->where('is_active', true)
                        ->orderBy('order');
                }, 'items.children' => function($query) {
                    $query->where('is_active', true)
                        ->orderBy('order');
                }])
                ->first();
        } else {
            $menu = \App\Menu::where('location', $identifier)
                ->where('is_active', true)
                ->with(['items' => function($query) {
                    $query->whereNull('parent_id')
                        ->where('is_active', true)
                        ->orderBy('order');
                }, 'items.children' => function($query) {
                    $query->where('is_active', true)
                        ->orderBy('order');
                }])
                ->first();
        }

        if (!$menu || $menu->items->isEmpty()) {
            return '';
        }

        $html = '<ul class="' . $cssClass . '">';

        foreach ($menu->items as $item) {
            $html .= renderMenuItem($item);
        }

        $html .= '</ul>';

        return $html;
    }
}

if (!function_exists('renderMenuItem')) {
    /**
     * Render a single menu item with children
     *
     * @param \App\MenuItem $item
     * @param int $depth
     * @return string
     */
    function renderMenuItem($item, $depth = 0)
    {
        $hasChildren = $item->children->where('is_active', true)->count() > 0;
        $activeClass = isMenuItemActive($item) ? ' active' : '';
        $cssClass = $item->css_class ? ' ' . $item->css_class : '';

        $html = '<li class="nav-item' . ($hasChildren ? ' dropdown' : '') . $cssClass . '">';

        if ($hasChildren) {
            $html .= '<a class="nav-link dropdown-toggle' . $activeClass . '" href="' . getMenuItemUrl($item) . '" ';
            $html .= 'id="dropdown' . $item->id . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
        } else {
            $html .= '<a class="nav-link' . $activeClass . '" href="' . getMenuItemUrl($item) . '" target="' . $item->target . '">';
        }

        if ($item->icon) {
            $html .= '<i class="' . $item->icon . ' me-1"></i> ';
        }

        $html .= $item->title;
        $html .= '</a>';

        if ($hasChildren) {
            $html .= '<ul class="dropdown-menu" aria-labelledby="dropdown' . $item->id . '">';
            foreach ($item->children->where('is_active', true) as $child) {
                $childActiveClass = isMenuItemActive($child) ? ' active' : '';
                $html .= '<li><a class="dropdown-item' . $childActiveClass . '" href="' . getMenuItemUrl($child) . '" target="' . $child->target . '">';

                if ($child->icon) {
                    $html .= '<i class="' . $child->icon . ' me-1"></i> ';
                }

                $html .= $child->title . '</a></li>';
            }
            $html .= '</ul>';
        }

        $html .= '</li>';

        return $html;
    }
}

if (!function_exists('getMenuItemUrl')) {
    /**
     * Get URL for menu item
     *
     * @param \App\MenuItem $item
     * @return string
     */
    function getMenuItemUrl($item)
    {
        if ($item->url) {
            return $item->url;
        }

        if ($item->route) {
            try {
                return route($item->route);
            } catch (\Exception $e) {
                return '#';
            }
        }

        return '#';
    }
}

if (!function_exists('isMenuItemActive')) {
    /**
     * Check if menu item is active
     *
     * @param \App\MenuItem $item
     * @return bool
     */
    function isMenuItemActive($item)
    {
        $currentUrl = request()->url();
        $itemUrl = getMenuItemUrl($item);

        // Check exact match
        if ($currentUrl === $itemUrl) {
            return true;
        }

        // Check if current route matches item route
        if ($item->route && request()->routeIs($item->route)) {
            return true;
        }

        // Check if current URL starts with item URL (for parent pages)
        if ($itemUrl !== '#' && str_starts_with($currentUrl, $itemUrl)) {
            return true;
        }

        return false;
    }
}
