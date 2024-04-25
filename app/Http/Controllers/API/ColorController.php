<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Http\Traits\ApiHelpers;

class ColorController extends BaseController
{

    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Color::all();
        $data = collect(
            [
                'colors' => $data
            ]
        );
        return $this->onSuccess($data, 'Colors retrieved successfully.');
    }
}
