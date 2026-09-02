<?php

namespace App\Http\Controllers\Admin;

use App\Business;
use App\BusinessCategory;
use App\City;
use App\State;
use App\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Str;

class BusinessController extends Controller
{
    public function index()
    {
        $categories = BusinessCategory::pluck('name', 'id')->toArray();
        return view('admin.business.index', compact('categories'));
    }

    public function create()
    {
        $categories = BusinessCategory::active()->pluck('name', 'id')->toArray();
        $countries = Country::pluck('country', 'country_id')->toArray();
        $states = State::pluck('state', 'state_id')->toArray();
        $cities = City::take(200)->pluck('city', 'city_id')->toArray();
        return view('admin.business.add', compact('categories', 'countries', 'states', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'category_id' => 'required|integer',
            'phone' => 'required|string|max:30',
            'address_line1' => 'required|string|max:255',
            'city_id' => 'required|integer',
        ]);

        $slug = Str::slug($request->name);
        $count = Business::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $business = new Business($request->except(['logo', 'cover_image']));
        $business->slug = $slug;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'biz_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('business_images'), $fileName);
            $business->logo = $fileName;
        }

        $business->save();
        flash('Business created successfully!')->success();
        return redirect()->route('admin.list.businesses');
    }

    public function edit($id)
    {
        $business = Business::with(['user', 'category', 'city'])->findOrFail($id);
        $categories = BusinessCategory::active()->pluck('name', 'id')->toArray();
        $countries = Country::pluck('country', 'country_id')->toArray();
        $states = State::where('country_id', $business->country_id ?: 1)->pluck('state', 'state_id')->toArray();
        $cities = City::where('state_id', $business->state_id)->pluck('city', 'city_id')->toArray();
        $packages = \App\Package::where('package_for', 'business')->orderBy('package_price', 'asc')->get();

        return view('admin.business.edit', compact('business', 'categories', 'countries', 'states', 'cities', 'packages'));
    }

    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);
        $business->fill($request->except(['logo', 'cover_image']));

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'biz_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('business_images'), $fileName);
            $business->logo = $fileName;
        }

        if ($request->filled('package_id')) {
            $business->package_id = $request->input('package_id');
            // If business has owner, sync owner's package as well
            if ($business->user) {
                $pkg = \App\Package::find($request->input('package_id'));
                if ($pkg) {
                    $business->user->business_package_id = $pkg->id;
                    $business->user->business_listings_quota = $pkg->package_num_listings;
                    $business->user->save();
                }
            }
        }

        $business->save();
        flash('Business updated successfully!')->success();
        return redirect()->route('admin.list.businesses');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        Business::where('id', $id)->delete();
        return 'ok';
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids) && is_array($ids)) {
            Business::whereIn('id', $ids)->delete();
            return response()->json(['status' => 'ok', 'count' => count($ids)]);
        }
        return response()->json(['status' => 'error', 'message' => 'No items selected']);
    }

    public function toggleVerification(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status'); // verified, pending, rejected, unverified
        $biz = Business::findOrFail($id);
        $biz->verification_status = $status;
        if ($status === 'verified') {
            $biz->verified_at = now();
        }
        $biz->save();
        return response()->json(['status' => 'ok', 'verification_status' => $status]);
    }

    public function toggleFeatured(Request $request)
    {
        $id = $request->input('id');
        $biz = Business::findOrFail($id);
        $biz->is_featured = $biz->is_featured ? 0 : 1;
        $biz->save();
        return response()->json(['status' => 'ok', 'is_featured' => $biz->is_featured]);
    }

    public function toggleActive(Request $request)
    {
        $id = $request->input('id');
        $biz = Business::findOrFail($id);
        $biz->is_active = $biz->is_active ? 0 : 1;
        $biz->save();
        return response()->json(['status' => 'ok', 'is_active' => $biz->is_active]);
    }

    public function fetchData(Request $request)
    {
        $businesses = Business::with(['category', 'city', 'user'])
            ->select('businesses.*');

        return Datatables::of($businesses)
            ->filter(function ($query) use ($request) {
                if ($request->has('name') && !empty($request->name)) {
                    $query->where('businesses.name', 'like', "%{$request->name}%");
                }
                if ($request->has('category_id') && !empty($request->category_id)) {
                    $query->where('businesses.category_id', $request->category_id);
                }
                if ($request->has('verification_status') && !empty($request->verification_status)) {
                    $query->where('businesses.verification_status', $request->verification_status);
                }
            })
            ->addColumn('checkbox', function ($biz) {
                return '<input type="checkbox" class="row-checkbox" value="' . $biz->id . '" style="cursor:pointer;width:17px;height:17px;" />';
            })
            ->addColumn('logo', function ($biz) {
                if ($biz->logo) {
                    return '<img src="' . $biz->getLogoUrl() . '" style="width:36px;height:36px;border-radius:6px;object-fit:cover;border:1px solid #E2E8F0;" alt="" />';
                }
                return '<div style="width:36px;height:36px;border-radius:6px;background:#F1F5F9;display:inline-flex;align-items:center;justify-content:center;color:#94A3B8;font-size:16px;"><i class="fa fa-building"></i></div>';
            })
            ->addColumn('category_name', function ($biz) {
                return $biz->category ? $biz->category->name : '-';
            })
            ->addColumn('city_name', function ($biz) {
                return $biz->city ? $biz->city->city : '-';
            })
            ->addColumn('package_badge', function ($biz) {
                $pkg = null;
                if ($biz->package_id) {
                    $pkg = \App\Package::find($biz->package_id);
                } elseif ($biz->user && $biz->user->business_package_id) {
                    $pkg = \App\Package::find($biz->user->business_package_id);
                }
                if ($pkg) {
                    if ($pkg->package_price >= 999) {
                        return '<span class="label label-danger" style="font-weight:700;padding:3px 8px;border-radius:6px;"><i class="fa fa-diamond"></i> ' . e($pkg->package_title) . '</span>';
                    } elseif ($pkg->package_price > 0) {
                        return '<span class="label label-primary" style="font-weight:700;padding:3px 8px;border-radius:6px;"><i class="fa fa-certificate"></i> ' . e($pkg->package_title) . '</span>';
                    }
                    return '<span class="label label-info" style="font-weight:700;padding:3px 8px;border-radius:6px;">' . e($pkg->package_title) . '</span>';
                }
                return '<span class="label label-default" style="color:#64748B;">Free / None</span>';
            })
            ->addColumn('verification_badge', function ($biz) {
                if ($biz->verification_status === 'verified') {
                    return '<span class="label label-success" style="cursor:pointer;" onclick="toggleVerify(' . $biz->id . ', \'unverified\')" title="Click to Unverify"><i class="fa fa-check-circle"></i> Verified</span>';
                } elseif ($biz->verification_status === 'pending') {
                    return '<span class="label label-warning" style="cursor:pointer;" onclick="toggleVerify(' . $biz->id . ', \'verified\')" title="Click to Approve"><i class="fa fa-clock-o"></i> Pending</span>';
                }
                return '<span class="label label-default" style="cursor:pointer;" onclick="toggleVerify(' . $biz->id . ', \'verified\')" title="Click to Verify"><i class="fa fa-times-circle"></i> Unverified</span>';
            })
            ->addColumn('featured_badge', function ($biz) {
                if ($biz->is_featured) {
                    return '<span class="label label-info" style="cursor:pointer;" onclick="toggleFeatured(' . $biz->id . ')"><i class="fa fa-star"></i> Featured</span>';
                }
                return '<span class="label label-default" style="cursor:pointer;" onclick="toggleFeatured(' . $biz->id . ')">Standard</span>';
            })
            ->addColumn('action', function ($biz) {
                $viewUrl = route('business.detail', $biz->slug);
                return '
                <div class="cms-action-wrap" style="display:flex;gap:5px;justify-content:center;">
                    <a href="' . $viewUrl . '" target="_blank" class="btn btn-xs btn-default" title="View live listing">
                        <i class="fa fa-external-link"></i> View
                    </a>
                    <a href="' . route('admin.edit.business', ['id' => $biz->id]) . '" class="btn btn-xs btn-primary" title="Edit listing">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                    <button type="button" onclick="delete_biz(' . $biz->id . ');" class="btn btn-xs btn-danger" title="Delete listing">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['checkbox', 'logo', 'package_badge', 'verification_badge', 'featured_badge', 'action'])
            ->setRowId(function ($biz) {
                return 'biz_row_' . $biz->id;
            })
            ->make(true);
    }
}
