<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use Carbon\Carbon;

class ChartsController extends Controller
{
    // GET LEADS COUNT FOR CHART DATA
    public function get_count() {
        $data = [
            'leads' => Leads::count(),
            'firstcall' => Leads::where('status', 'first_call')->count(),
            'followup' => Leads::where('status', 'followup')->count(),
            'demo' => Leads::where('status', 'demo')->count(),
            'training' => Leads::where('status', 'training')->count(),
            'sales' => Leads::where('status', 'sales')->count()
        ];
        
        return json_encode($data);
    }

    // get monthly leads from the current year
    public function get_count_month($status) {
        $data = Leads::where('status', $status)
            ->where('updated_at', '>', Carbon::now()->startOfYear())
            ->where('updated_at', '<', Carbon::now()->endOfYear())
            ->get()->groupBy(function($item) {
            return $item->updated_at->format('M');
        });

        return $data;
    }

    // get daily leads from the current week
    public function get_count_day($status) {
        $data = Leads::where('status', $status)
            ->where(function($item) {
                $item->where('updated_at', '>', Carbon::now()->startOfWeek())
                    ->where('updated_at', '<', Carbon::now()->endOfWeek());
            })
            ->get()->groupBy(function($item) {
                return $item->updated_at->format('D');
            });
        return $data;
    }

    // get weekly leads from the current month
    public function get_count_week($status) {
        $data = Leads::where('status', $status)
            ->where(function($item) {
                $item->where('updated_at', '>', Carbon::now()->startOfMonth())
                    ->where('updated_at', '<', Carbon::now()->endOfMonth());
            })
            ->get()->groupBy(function($item) {
                return $item->updated_at->format('W');
            });
        return $data;
    }
}
