<?php

namespace App\Http\Controllers\Admin;

use App\BusinessCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Str;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        return view('admin.business_category.index');
    }

    public function create()
    {
        return view('admin.business_category.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
        ]);

        $slug = Str::slug($request->name);
        $count = BusinessCategory::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $cat = new BusinessCategory($request->all());
        $cat->slug = $slug;
        $cat->save();

        flash('Business Category created successfully!')->success();
        return redirect()->route('admin.list.business_categories');
    }

    public function edit($id)
    {
        $category = BusinessCategory::findOrFail($id);
        return view('admin.business_category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $cat = BusinessCategory::findOrFail($id);
        $cat->fill($request->all());
        $cat->save();

        flash('Business Category updated successfully!')->success();
        return redirect()->route('admin.list.business_categories');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        BusinessCategory::where('id', $id)->delete();
        return 'ok';
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids) && is_array($ids)) {
            BusinessCategory::whereIn('id', $ids)->delete();
            return response()->json(['status' => 'ok', 'count' => count($ids)]);
        }
        return response()->json(['status' => 'error', 'message' => 'No items selected']);
    }

    public function fetchData(Request $request)
    {
        $categories = BusinessCategory::select('business_categories.*');

        return Datatables::of($categories)
            ->filter(function ($query) use ($request) {
                if ($request->has('name') && !empty($request->name)) {
                    $query->where('name', 'like', "%{$request->name}%");
                }
            })
            ->addColumn('checkbox', function ($cat) {
                return '<input type="checkbox" class="row-checkbox" value="' . $cat->id . '" style="cursor:pointer;width:17px;height:17px;" />';
            })
            ->addColumn('icon_display', function ($cat) {
                return '<i class="fa ' . ($cat->icon ?: 'fa-folder-o') . '" style="font-size:18px;color:#3B82F6;"></i> ' . $cat->name;
            })
            ->addColumn('action', function ($cat) {
                return '
                <div style="display:flex;gap:5px;justify-content:center;">
                    <a href="' . route('admin.edit.business_category', ['id' => $cat->id]) . '" class="btn btn-xs btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                    <button type="button" onclick="delete_category(' . $cat->id . ');" class="btn btn-xs btn-danger">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['checkbox', 'icon_display', 'action'])
            ->setRowId(function ($cat) {
                return 'cat_row_' . $cat->id;
            })
            ->make(true);
    }
}
