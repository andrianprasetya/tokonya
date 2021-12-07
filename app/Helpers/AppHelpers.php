<?php

if (!function_exists('odk_admin_sidebar')) {
    function odk_admin_sidebar($withRole = true)
    {
        $menuData = \App\Models\Menu::adminSidebar($withRole)->get()->toArray();
        return $menuData;
    }
}
