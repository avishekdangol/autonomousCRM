<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public function users() {
        return $this->hasMany(User::class);
    }

    public function licenses() {
        return $this->hasMany(License::class);
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'due_date'
        ];
    }
}
