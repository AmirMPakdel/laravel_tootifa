<?php

namespace App\Http\Controllers\API\Main;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\ActivationPackage;
use App\Models\IncrementalPackage;
use Illuminate\Http\Request;

class PackagesController extends BaseController
{
    public function fetchIncrementalPackages(Request $request)
    {
        $pt = $request->input('package_type');

        if ($pt == Constant::$PT_INCREMENTAL)
            $packages = IncrementalPackage::where('type', $request->input('type'))->get()->map(function ($package) {
                return [
                    'id' => $package->id,
                    'title' => $package->title,
                    'price' => $package->price,
                    'value' => $package->value,
                ];
            });
        else
            $packages = ActivationPackage::where('type', $request->input('type'))->get()->map(function ($package) {
                return [
                    'id' => $package->id,
                    'title' => $package->title,
                    'price' => $package->price,
                    'days' => $package->days,
                ];
            });

        return $this->sendResponse(Constant::$SUCCESS, $packages);
    }
}
