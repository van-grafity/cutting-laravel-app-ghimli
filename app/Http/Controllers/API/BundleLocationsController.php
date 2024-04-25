<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Traits\ApiHelpers;

use App\Models\BundleLocation;

class BundleLocationsController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BundleLocation::all();
        $data = [
            'status' => 'success',
            'bundle_locations' => $data,
            'message_data' => 'Berhasil mendapatkan data Bundle Location',
        ];
        return $this->onSuccess($data, 'Bundle Locations retrieved successfully.');
    }
}
