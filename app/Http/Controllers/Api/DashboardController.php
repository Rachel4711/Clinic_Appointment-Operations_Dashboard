<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{Appointment,IssueReport};
use Illuminate\Http\Request;
class DashboardController extends Controller {
    public function __invoke(Request $r){$q=Appointment::query();if($r->user()->role==='doctor')$q->where('doctor_id',optional($r->user()->doctor)->id ?: 0);$today=(clone $q)->whereDate('appointment_date',today());$counts=['total'=>(clone $today)->count(),'scheduled'=>(clone $today)->where('status','Scheduled')->count(),'completed'=>(clone $today)->where('status','Completed')->count(),'cancelled'=>(clone $today)->where('status','Cancelled')->count(),'waiting'=>(clone $today)->where('status','Checked In')->count(),'open_issues'=>IssueReport::whereIn('status',['Open','In Progress'])->when(!$r->user()->isAdmin(),fn($x)=>$x->where('reported_by',$r->user()->id))->count()];$byStatus=(clone $today)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total','status');return response()->json(['data'=>['counts'=>$counts,'by_status'=>$byStatus,'recent_appointments'=>(clone $q)->with('patient:id,full_name','doctor:id,name')->latest()->limit(6)->get(),'recent_issues'=>IssueReport::with('reporter:id,name')->when(!$r->user()->isAdmin(),fn($x)=>$x->where('reported_by',$r->user()->id))->latest()->limit(5)->get()]]);}
}
