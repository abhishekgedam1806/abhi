<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SiteMenuItem;
use App\SiteSetting;

class MenuManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display Header Menu Management Page
     */
    public function manageHeader()
    {
        $headerItems = SiteMenuItem::where('menu_type', 'header')
            ->orderBy('order_num', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $siteSetting = SiteSetting::first();

        return view('admin.menu_management.header', compact('headerItems', 'siteSetting'));
    }

    /**
     * Display Footer Menu Management Page
     */
    public function manageFooter()
    {
        $col1Items = SiteMenuItem::where('menu_type', 'footer_col1')
            ->orderBy('order_num', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $col2Items = SiteMenuItem::where('menu_type', 'footer_col2')
            ->orderBy('order_num', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $col3Items = SiteMenuItem::where('menu_type', 'footer_col3')
            ->orderBy('order_num', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $cityItems = SiteMenuItem::where('menu_type', 'footer_cities')
            ->orderBy('order_num', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $siteSetting = SiteSetting::first();

        return view('admin.menu_management.footer', compact(
            'col1Items',
            'col2Items',
            'col3Items',
            'cityItems',
            'siteSetting'
        ));
    }

    /**
     * Store a new Menu Item
     */
    public function storeMenuItem(Request $request)
    {
        $request->validate([
            'menu_type' => 'required|string',
            'title'     => 'required|string|max:190',
            'url'       => 'required|string|max:255',
        ]);

        $maxOrder = SiteMenuItem::where('menu_type', $request->menu_type)->max('order_num');

        $item = new SiteMenuItem();
        $item->menu_type    = $request->menu_type;
        $item->title        = trim($request->title);
        $item->url          = trim($request->url);
        $item->icon         = $request->filled('icon') ? trim($request->icon) : null;
        $item->target       = $request->input('target', '_self');
        $item->audience     = $request->input('audience', 'all');
        $item->order_num    = $request->filled('order_num') ? (int)$request->order_num : (($maxOrder ?: 0) + 1);
        $item->is_active    = $request->has('is_active') ? 1 : 1;
        $item->custom_class = $request->filled('custom_class') ? trim($request->custom_class) : null;
        $item->save();

        SiteMenuItem::clearMenuCache();

        flash(__('Menu item added successfully!'))->success();
        return redirect()->back();
    }

    /**
     * Update an existing Menu Item
     */
    public function updateMenuItem(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:190',
            'url'   => 'required|string|max:255',
        ]);

        $item = SiteMenuItem::findOrFail($id);
        $item->title        = trim($request->title);
        $item->url          = trim($request->url);
        $item->icon         = $request->filled('icon') ? trim($request->icon) : null;
        $item->target       = $request->input('target', '_self');
        $item->audience     = $request->input('audience', 'all');
        if ($request->has('order_num')) {
            $item->order_num = (int)$request->order_num;
        }
        if ($request->has('is_active')) {
            $item->is_active = (int)$request->is_active;
        }
        $item->custom_class = $request->filled('custom_class') ? trim($request->custom_class) : null;
        $item->save();

        SiteMenuItem::clearMenuCache();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Updated successfully']);
        }

        flash(__('Menu item updated successfully!'))->success();
        return redirect()->back();
    }

    /**
     * Delete a Menu Item
     */
    public function deleteMenuItem($id)
    {
        $item = SiteMenuItem::findOrFail($id);
        $item->delete();

        SiteMenuItem::clearMenuCache();

        flash(__('Menu item deleted successfully!'))->success();
        return redirect()->back();
    }

    /**
     * Toggle Active / Inactive Status via AJAX
     */
    public function toggleStatus($id)
    {
        $item = SiteMenuItem::findOrFail($id);
        $item->is_active = $item->is_active ? 0 : 1;
        $item->save();

        SiteMenuItem::clearMenuCache();

        return response()->json([
            'success'   => true,
            'is_active' => $item->is_active,
            'message'   => 'Status updated successfully'
        ]);
    }

    /**
     * Update Ordering of Menu Items
     */
    public function reorderMenuItems(Request $request)
    {
        $orders = $request->input('orders', []); // [ ['id' => 1, 'order_num' => 1], ... ]

        foreach ($orders as $orderData) {
            if (isset($orderData['id']) && isset($orderData['order_num'])) {
                SiteMenuItem::where('id', $orderData['id'])->update(['order_num' => (int)$orderData['order_num']]);
            }
        }

        SiteMenuItem::clearMenuCache();

        return response()->json(['success' => true, 'message' => 'Menu reordered successfully']);
    }

    /**
     * Update Header Global Settings
     */
    public function updateHeaderSettings(Request $request)
    {
        $siteSetting = SiteSetting::first();
        if ($siteSetting) {
            $siteSetting->header_show_post_job     = $request->has('header_show_post_job') ? 1 : 0;
            $siteSetting->header_show_notifications = $request->has('header_show_notifications') ? 1 : 0;
            $siteSetting->save();
        }

        SiteMenuItem::clearMenuCache();

        flash(__('Header settings updated successfully!'))->success();
        return redirect()->back();
    }

    /**
     * Update Footer Global Settings
     */
    public function updateFooterSettings(Request $request)
    {
        $siteSetting = SiteSetting::first();
        if ($siteSetting) {
            $siteSetting->footer_col1_title          = $request->input('footer_col1_title', 'Quick Links');
            $siteSetting->footer_col2_title          = $request->input('footer_col2_title', 'Jobs By Functional Area');
            $siteSetting->footer_col3_title          = $request->input('footer_col3_title', 'Jobs By Industry');
            $siteSetting->footer_col4_title          = $request->input('footer_col4_title', 'Contact Us');
            $siteSetting->footer_show_popular_cities = $request->has('footer_show_popular_cities') ? 1 : 0;
            $siteSetting->footer_show_payment_icons  = $request->has('footer_show_payment_icons') ? 1 : 0;
            $siteSetting->footer_copyright_text     = $request->input('footer_copyright_text');
            $siteSetting->save();
        }

        SiteMenuItem::clearMenuCache();

        flash(__('Footer settings updated successfully!'))->success();
        return redirect()->back();
    }
}
