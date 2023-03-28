<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Remark;
use App\Http\Traits\ApiHelpers;

class RemarksController extends Controller
{
    use ApiHelpers;

    public function index()
    {
        $data = Remark::all();
        $data = collect(
            [
                'cutting_record_remark' => $data
            ]
        );
        return $this->onSuccess($data, 'Remarks retrieved successfully.');
    }
}
