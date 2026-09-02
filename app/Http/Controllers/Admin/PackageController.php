<?php

namespace App\Http\Controllers\Admin;

use DB;
use Input;
use Redirect;
use App\Package;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DataTables;
use App\Http\Requests\PackageFormRequest;
use App\Http\Controllers\Controller;

class PackageController extends Controller
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

    public function indexPackages()
    {
        return view('admin.package.index');
    }

    public function createPackage()
    {
        return view('admin.package.add');
    }

    public function storePackage(PackageFormRequest $request)
    {
        $package = new Package();

        $package->package_title = $request->input('package_title');
        $package->package_price = $request->input('package_price');
        $package->package_num_days = $request->input('package_num_days');
        $package->package_num_listings = $request->input('package_num_listings');
        $package->package_num_services = $request->input('package_num_services', 5);
        $package->package_num_photos = $request->input('package_num_photos', 3);
        $package->is_featured = $request->input('is_featured', 0);
        $package->has_whatsapp_leads = $request->input('has_whatsapp_leads', 1);
        $package->has_verified_badge = $request->input('has_verified_badge', 0);
        $package->package_for = $request->input('package_for');
        $package->save();
        /*         * ************************************ */
        flash('Package has been added!')->success();
        return \Redirect::route('edit.package', array($package->id));
    }

    public function editPackage($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.package.edit')
                        ->with('package', $package);
    }

    public function updatePackage($id, PackageFormRequest $request)
    {
        $package = Package::findOrFail($id);

        $package->package_title = $request->input('package_title');
        $package->package_price = $request->input('package_price');
        $package->package_num_days = $request->input('package_num_days');
        $package->package_num_listings = $request->input('package_num_listings');
        $package->package_num_services = $request->input('package_num_services', 5);
        $package->package_num_photos = $request->input('package_num_photos', 3);
        $package->is_featured = $request->input('is_featured', 0);
        $package->has_whatsapp_leads = $request->input('has_whatsapp_leads', 1);
        $package->has_verified_badge = $request->input('has_verified_badge', 0);
        $package->package_for = $request->input('package_for');

        $package->update();
        flash('Package has been updated!')->success();
        return \Redirect::route('edit.package', array($package->id));
    }

    public function deletePackage(Request $request)
    {
        $id = $request->input('id');
        try {
            $package = Package::findOrFail($id);
            $package->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchPackagesData(Request $request)
    {
        $packages = Package::select([
                    'packages.id',
                    'packages.package_title',
                    'packages.package_price',
                    'packages.package_num_days',
                    'packages.package_num_listings',
                    'packages.package_for',
                ])->orderBy('packages.package_for');
        return Datatables::of($packages)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('package_title') && !empty($request->package_title)) {
                                $query->where('packages.package_title', 'like', "%{$request->get('package_title')}%");
                            }
                            if ($request->has('package_price') && !empty($request->package_price)) {
                                $query->where('packages.package_price', 'like', "{$request->get('package_price')}%");
                            }
                            if ($request->has('package_num_days') && !empty($request->package_num_days)) {
                                $query->where('packages.package_num_days', 'like', "{$request->get('package_num_days')}%");
                            }

                            if ($request->has('package_num_listings') && !empty($request->package_num_listings)) {
                                $query->where('packages.package_num_listings', 'like', "{$request->get('package_num_listings')}%");
                            }

                            if ($request->has('package_for') && !empty($request->package_for)) {
                                $query->where('packages.package_for', 'like', "{$request->get('package_for')}");
                            }
                        })
                        ->addColumn('checkbox', function ($packages) {
                            return '<input type="checkbox" class="row-checkbox" value="' . $packages->id . '" style="cursor:pointer;width:17px;height:17px;" />';
                        })
                        ->addColumn('action', function ($packages) {
                            return '
				<div class="btn-group" style="white-space:nowrap;">
					<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background:#4F46E5;border-color:#4F46E5;color:#fff;font-size:12px;font-weight:600;padding:5px 11px;border-radius:5px;display:inline-flex;align-items:center;gap:5px;">
                        Action <i class="fa fa-angle-down" aria-hidden="true"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right" style="min-width:140px;padding:6px 0;border-radius:8px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.12);border:1px solid #e2e8f0;z-index:10050;">
						<li>
							<a href="' . route('edit.package', ['id' => $packages->id]) . '" style="padding:8px 14px;display:flex;align-items:center;gap:8px;font-size:12.5px;color:#334155;"><i class="fa fa-pencil text-primary" style="width:16px;"></i> Edit</a>
						</li>						
						<li>
							<a href="javascript:void(0);" onclick="deletePackage(' . $packages->id . ');" style="padding:8px 14px;display:flex;align-items:center;gap:8px;font-size:12.5px;color:#dc2626;"><i class="fa fa-trash-o text-danger" style="width:16px;"></i> Delete</a>
						</li>
					</ul>
				</div>';
                        })
                        ->rawColumns(['checkbox', 'action'])
                        ->setRowId(function($packages) {
                            return 'packageDtRow' . $packages->id;
                        })
                        ->make(true);
        //$query = $dataTable->getQuery()->get();
        //return $query;
    }

    public function bulkDeletePackages(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids) && is_array($ids)) {
            try {
                Package::whereIn('id', $ids)->delete();
                return response()->json(['status' => 'ok', 'count' => count($ids)]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'No items selected']);
    }

}
