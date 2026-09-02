<?php

namespace App\Http\Controllers\Admin;

use Auth;
use DB;
use Input;
use Carbon\Carbon;
use Redirect;
use App\Cms;
use App\CmsContent;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Language;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DataTables;
use App\Http\Requests\CmsFormRequest;
use App\Http\Controllers\Controller;

class CmsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCms()
    {
        return view('admin.cms.index');
    }

    public function createCms()
    {
        return view('admin.cms.add');
    }

    public function storeCms(CmsFormRequest $request)
    {
        $cms = new Cms();
        $cms->page_slug = $request->input('page_slug');
        $cms->seo_title = $request->input('seo_title');
        $cms->seo_description = $request->input('seo_description');
        $cms->seo_keywords = $request->input('seo_keywords');
        $cms->seo_other = $request->input('seo_other');
        $cms->show_in_top_menu = $request->input('show_in_top_menu');
        $cms->show_in_footer_menu = $request->input('show_in_footer_menu');
        $cms->save();
        flash('C.M.S page has been added!')->success();
        return \Redirect::route('edit.cms', array($cms->id));
    }

    public function editCms($id)
    {
        $cms = Cms::findOrFail($id);
        $cmsContent = CmsContent::where('page_id', $cms->id)->first();
        if (!$cmsContent) {
            $cmsContent = new CmsContent();
            $cmsContent->page_id = $cms->id;
            $cmsContent->page_title = ucfirst(str_replace('-', ' ', $cms->page_slug));
            $cmsContent->page_content = '';
            $cmsContent->lang = 'en';
            $cmsContent->save();
        }
        return view('admin.cms.edit', compact('cms', 'cmsContent'));
    }

    public function updateCms($id, CmsFormRequest $request)
    {
        $cms = Cms::findOrFail($id);
        $cms->page_slug = $request->input('page_slug');
        $cms->seo_title = $request->input('seo_title');
        $cms->seo_description = $request->input('seo_description');
        $cms->seo_keywords = $request->input('seo_keywords');
        $cms->seo_other = $request->input('seo_other');
        $cms->show_in_top_menu = $request->input('show_in_top_menu');
        $cms->show_in_footer_menu = $request->input('show_in_footer_menu');
        $cms->update();

        // Save / Update Page Content & Title
        $cmsContent = CmsContent::where('page_id', $cms->id)->first();
        if (!$cmsContent) {
            $cmsContent = new CmsContent();
            $cmsContent->page_id = $cms->id;
            $cmsContent->lang = 'en';
        }
        if ($request->has('page_title')) {
            $cmsContent->page_title = $request->input('page_title');
        }
        if ($request->has('page_content')) {
            $cmsContent->page_content = $request->input('page_content');
        }
        $cmsContent->save();

        flash('C.M.S page & content have been updated successfully!')->success();
        return \Redirect::route('edit.cms', array($cms->id));
    }

    public function deleteCms(Request $request)
    {
        $id = $request->input('id');
        try {
            $deletedCms = Cms::where('id', $id)->delete();
            $deletedCmsContent = CmsContent::where('page_id', $id)->delete();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

    public function bulkDeleteCms(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids) && is_array($ids)) {
            try {
                Cms::whereIn('id', $ids)->delete();
                CmsContent::whereIn('page_id', $ids)->delete();
                return response()->json(['status' => 'ok', 'count' => count($ids)]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'No items selected']);
    }

    public function fetchCmsData(Request $request)
    {
        $cms = Cms::select(
                        [
                            'cms.id',
                            'cms.page_slug',
                            'cms.seo_title',
                            'cms.seo_description',
                            'cms.seo_keywords',
                            'cms.seo_other',
                            'cms.created_at',
                            'cms.updated_at'
                        ]
        );
        return Datatables::of($cms)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('id') && !empty($request->id)) {
                                $query->where('cms.id', 'like', "{$request->get('id')}");
                            }
                            if ($request->has('page_slug') && !empty($request->page_slug)) {
                                $query->where('cms.page_slug', 'like', "%{$request->get('page_slug')}%");
                            }
                        })
                        ->addColumn('checkbox', function ($cms) {
                            return '<input type="checkbox" class="row-checkbox" value="' . $cms->id . '" style="cursor:pointer;width:17px;height:17px;" />';
                        })
                        ->addColumn('action', function ($cms) {
                            $viewUrl = !empty($cms->page_slug) ? route('cms', $cms->page_slug) : '#';
                            return '
                            <div class="cms-action-wrap">
                                <a href="' . $viewUrl . '" target="_blank" class="btn-cms-view" title="View live page">
                                    <i class="fa fa-external-link"></i> View
                                </a>
                                <a href="' . route('edit.cms', ['id' => $cms->id]) . '" class="btn-cms-edit" title="Edit CMS settings">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <button type="button" onclick="delete_cms(' . $cms->id . ');" class="btn-cms-delete" title="Delete CMS page">
                                    <i class="fa fa-trash-o"></i> Delete
                                </button>
                            </div>';
                        })
                        ->rawColumns(['checkbox', 'action'])
                        ->setRowId(function($cms) {
                            return 'cms_dt_row_' . $cms->id;
                        })
                        ->make(true);
    }

}
