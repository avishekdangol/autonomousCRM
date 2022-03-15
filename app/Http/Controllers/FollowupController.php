<?php

namespace App\Http\Controllers;

use App\Http\Resources\Followups as ResourcesFollowups;
use App\Models\Leads;

class FollowupController extends Controller
{
    // get all the followups made for a particular lead
    public function get_followups($id) {
        $followups = Leads::findOrFail($id)->followups()->paginate(10);
        return ResourcesFollowups::collection($followups);
    }
}
