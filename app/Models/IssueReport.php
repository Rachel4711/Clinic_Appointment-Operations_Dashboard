<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class IssueReport extends Model {
    use HasFactory;
    protected $fillable = ['reported_by','title','description','priority','status','assigned_to','resolution_notes','resolved_at'];
    protected $casts = ['resolved_at'=>'datetime'];
    public function reporter(){ return $this->belongsTo(User::class, 'reported_by'); }
    public function assignee(){ return $this->belongsTo(User::class, 'assigned_to'); }
}
