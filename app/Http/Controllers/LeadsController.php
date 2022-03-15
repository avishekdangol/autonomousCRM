<?php

namespace App\Http\Controllers;

use App\Events\Leads as EventsLeads;
use App\Http\Resources\Leads as ResourcesLeads;
use App\Models\Followups;
use App\Models\Leads;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class LeadsController extends Controller
{

    // CREATE NEW LEAD
    public function create(Request $request) {
        $data = $request->all();
        
        $lead = new Leads();
        $lead->name = $data['name'];
        $lead->address = $data['address'];
        $lead->email = $data['email'];
        $lead->person = $data['person'];
        $lead->phone = $data['phone'];
        $lead->source = $data['source'];
        $lead->quality = $data['quality'];
        $lead->followup_by = $data['followup_by'];
        $lead->followup_date = $data['followup_date'];
        $lead->remarks = $data['remarks'];
        $lead->save();

        $this->followup($lead);

        return 'created';
    }

    // UPDATE LEADS
    public function update(Request $request) {
        $data = $request->all();
        $lead = Leads::findOrFail($data['id']);

        $lead->name = $data['name'];
        $lead->address = $data['address'];
        $lead->email = $data['email'];
        $lead->person = $data['person'];
        $lead->phone = $data['phone'];
        $lead->source = $data['source'];
        $lead->quality = $data['quality'];
        $lead->followup_by = $data['followup_by'];
        $lead->status = $data['status'];
        $lead->remarks = $data['remarks'];
        if ($data['last_followup_date'] != null) $lead->followup_date = $data['last_followup_date'];
        $lead->save();

        if ($data['last_followup_date'] != null) $this->followup($lead);
        return $request;
    }

    // CREATE FOLLOWUP
    private function followup($data) {
        $followup = new followups();

        $followup->leads_id = $data['id'];
        $followup->followup_date = $data['followup_date'];
        $followup->status = $data['status'] ?? 'first_call';
        $followup->save();
    }

    // DELETE LEADS
    public function destroy($id) {
        Followups::where('leads_id', $id)->delete();
        $affected_row = Leads::findOrFail($id)->delete();
        if ($affected_row) return true;
        else return false;
    }

    // QUERY LEADS
    public function query($query) {
        $leads = Leads::where('name', 'like', '%' . $query . '%')
        ->orwhere('phone', '=', $query)
        ->orwhere('address', 'like', '%' . $query . '%')
        ->paginate(10);
        return ResourcesLeads::collection($leads);
    }

    // QUERY LEADS FOR SPECIFIC USER
    public function user_query($id, $query) {
        $user = User::findOrFail($id);
        $leads = Leads::where('name', 'like', '%' . $query . '%')
        ->orwhere('phone', '=', $query)
        ->orwhere('address', 'like', '%' . $query . '%')
        ->where('followup_by', $user->name)
        ->paginate(10);
        return ResourcesLeads::collection($leads);
    }

    // GET DUE LEADS FOR TODAY
    public function get_api_today($status) {
        $leads = Leads::where('status', $status)
            ->where('followup_date', Carbon::today()->toDateString())
            ->paginate(10);
        return ResourcesLeads::collection($leads);
    }

    // GET DUE LEADS OF SPECIFIC USER FOR TODAY
    public function get_api_user_today($status, $id) {
        $user = User::findOrFail($id);

        $leads = Leads::where('status', $status)
            ->where('followup_by', $user->name)
            ->where('followup_date', Carbon::today()->toDateString())
            ->paginate(10);
        return ResourcesLeads::collection($leads);
    }

    // GET ALL LEADS FOR SPECIFIC STATUS
    public function get_api_all($status = 'first_call') {
        $leads = Leads::where('status', $status)
            ->paginate(10);
        return ResourcesLeads::collection($leads);
    }

    // GET ALL LEADS OF SPECIFIC STATUS FOR SPECIFIC USER
    public function get_api_user_all($status, $id) {
        $user = User::findOrFail($id);

        $leads = Leads::where('status', $status)
            ->where('followup_by', $user->name)
            ->orderBy('followup_date')
            ->paginate(10);

        return Leads::collection($leads);
    }

    // GET LEADS OF ALL STATUS
    public function get_all_leads() {
        $leads = Leads::orderBy('followup_date')->paginate(10);
        return ResourcesLeads::collection($leads);
    }

    // GET LEADS OF ALL STATUS FOR SPECIFIC USER
    public function get_users_all_leads($id) {
        $user = User::findOrFail($id);

        $leads = Leads::where('followup_by', $user->name)
            ->orderBy('followup_date')
            ->paginate(10);
        return ResourcesLeads::collection($leads);
    }

    // CREATE AN EVENT FOR THE LISTENER
    public function event(Request $request) { 
        broadcast(new EventsLeads($request->all()));
    }
    
}