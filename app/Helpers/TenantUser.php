<?php

namespace App\Helpers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class TenantUser {
    // constructor
    public function __construct($tenant, $data) {
        $this->tenant = $tenant;
        $this->data = $data;
    }

    // create an admin for the newly created tenant
    public function create_tenant_user() {
        try {
            $this->tenant->run(function() {
                User::create([
                    'name' => $this->data['name'],
                    'username' => $this->data['domain'],
                    'password' => Hash::make($this->data['domain']),
                    'admin' => 1
                ]);
            });

            return $this->data;
        } catch(Exception $e) {
            return $e;
        }
    }

    // update the user in particular tenant database
    public function update_tenant_user() {
        try {
            $this->tenant->run(function() {
                User::where('username', $this->data['id'])->update([
                    'name' => $this->data['name']
                ]);
            });

            return $this->data;
        } catch(Exception $e) {
            return $e;
        }
    }
}
