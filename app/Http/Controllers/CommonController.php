<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    // Fetch the district and its associated province
    public function getProvince($district_id)
    {
        $district = District::where('id', $district_id)->first();

        if ($district) {
            $province = Province::select('id','name')->find($district->province_id);
            return response()->json(['province' => $province]);
        }

        return response()->json(['province' => null]);
    }
}
