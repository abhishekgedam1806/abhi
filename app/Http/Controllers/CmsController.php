<?php

namespace App\Http\Controllers;

use App\Cms;
use App\CmsContent;
use App\Helpers\SeoHelper;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    /**
     * Show CMS page by slug
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function getPage($slug)
    {
        $cms = Cms::where('page_slug', 'like', $slug)->first();
        if (!$cms) {
            abort(404);
        }

        $cmsContent = CmsContent::getContentByPageId($cms->id);
        if (!$cmsContent) {
            abort(404);
        }

        // Build robust SEO object using SeoHelper
        $defaultTitle = ($cmsContent->page_title ?: 'Information') . ' | JobNBiz';
        $defaultDesc  = strip_tags(substr((string)$cmsContent->page_content, 0, 155));
        $defaultKw    = 'jobnbiz, ' . strtolower((string)$cmsContent->page_title);

        $seo = SeoHelper::custom(
            $cms->seo_title ?: $defaultTitle,
            $cms->seo_description ?: $defaultDesc,
            $cms->seo_keywords ?: $defaultKw,
            'cms/' . $cms->page_slug
        );

        // Fetch other CMS pages for sidebar navigation
        $otherPages = Cms::join('cms_content', 'cms.id', '=', 'cms_content.page_id')
            ->select('cms.page_slug', 'cms_content.page_title')
            ->orderBy('cms.id', 'asc')
            ->get();

        return view('cms.cms_page', compact('cms', 'cmsContent', 'seo', 'otherPages'));
    }
}
