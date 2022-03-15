<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Models\Tenant;

class License extends Model
{
    public function tenant() {
        return $this->belongsTo(Tenant::class);
    }
}
