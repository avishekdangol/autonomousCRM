<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function create() {

    }

    public function get() {
        $licenses = License::get();
        return $licenses;
    }

    public function update() {

    }

    public function delete() {

    }
}
