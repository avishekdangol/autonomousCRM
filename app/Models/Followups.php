<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followups extends Model
{
    use HasFactory;
    public $protected = ['leads_id', 'followup_date'];

    public function Leads() {
        return $this->belongsTo(Leads::class);
    }
}
