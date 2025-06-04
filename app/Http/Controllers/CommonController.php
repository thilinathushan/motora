<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

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

    public function getSelectedModel(Request $request)
    {
        $validated = $request->validate([
            'model_version' => ['required', Rule::in(array_keys(Config::get('prediction_model.models')))]
        ]);

        session(['user_selected_model' => $validated['model_version']]);

        return response()->json([
            'message' => 'Model updated',
            'model' => $validated['model_version'],
        ]);
    }

    public function generatePassword()
    {
        $password = $this->createSecurePassword();
        
        return response()->json([
            'success' => true,
            'password' => $password,
            'message' => 'Password generated successfully!'
        ]);
    }

    private function createSecurePassword($length = 12)
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        $password = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];
        
        $allChars = $lowercase . $uppercase . $numbers . $symbols;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        return str_shuffle($password);
    }

}
