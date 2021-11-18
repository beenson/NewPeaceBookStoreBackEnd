<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BanRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([]);
        $this->middleware('admin')->except([]);
    }
}
