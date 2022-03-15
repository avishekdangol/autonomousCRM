<?php

namespace App\Http\Controllers;

use App\Helpers\TenantUser;
use App\Models\Tenant;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TenantController extends Controller
{

    // creates a new tenant, domain for the tenant and an admin for the tenant
    public function create(Request $request) {
        $data = $request->all();
        $domain = $data['domain'] . '.' . env('APP_BASE_URL');        

        try {
            // create new tenant
            $tenant = Tenant::create([
                'id' => $data['domain'],
                'name' => $data['name'],
                'due_date' => Carbon::today()->addYear()->toDateString(),
            ]);

            // create domain for the tenant
            $tenant->domains()->create(['domain' => $domain]);

            // create an admin for the tenant
            $userHelper = new TenantUser($tenant, $data);
            if ($user = $userHelper->create_tenant_user()) {
                return 200;
            } else return $user;

        } catch(Exception $e) {
            return $e;
        }  
    }

    // checks if the domain already exists
    public function check_domain($domain) {
        if (Tenant::find($domain)) return 'already-exists';
        else return 'valid';
    }

    // gets the clients
    public function get() {
        $tenants = Tenant::select('id', 'name', 'due_date')->get();
        return $tenants;
    }

    // gets recent five clients
    public function getRecent() {
        $tenants = Tenant::select('id', 'name')->latest()->limit(5)->get();
        return $tenants;
    }

    // gets clients closer to due date
    public function getDue() {
        $tenants = Tenant::select('id', 'name', 'due_date')
            ->where('due_date', Carbon::today()->toDateString())
            ->orWhere('due_date', '<', Carbon::today()->toDateString())
            ->orWhereBetween('due_date', [Carbon::today()->toDateString(), Carbon::today()->addWeek()->toDateString()])
            ->get();
        
        return $tenants;
    }

    // update the clients
    public function update(Request $request) {
        $data = $request->all();

        try {
            // Get the tenant to be updated
            $tenant = Tenant::findOrFail($data['id']);

            // Update it in the system database
            $tenant->name = $data['name'];
            $tenant->save();

            // Use the helper to update in the particular tenant database
            $userHelper = new TenantUser($tenant, $data);
            if ($user = $userHelper->update_tenant_user()) return 200;
            else return $user;

            return 200;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // delete the client and their domain
    public function delete($id) {
        try {
            $tenant = Tenant::find($id);

            $tenant->domains()->delete();
            $tenant->delete();

            return 200;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // delete clients in bulk and their domains
    public function bulkDelete(Request $request) {
        $ids = $request->all()['params'];

        try {
            foreach($ids as $id) {
                $this->delete($id);
            }

            return 200;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // renew clients
    public function renew($id) {
        try {
            $tenant = Tenant::find($id);

            $tenant->due_date = (new Carbon($tenant->due_date))->addYear()->toDateString();
            $tenant->save();

            return 200;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // renew clients in bulk
    public function bulkRenew(Request $request) {
        $ids = $request->all()['params'];

        try {
            foreach($ids as $id) {
                $this->renew($id);
            }

            return 200;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
