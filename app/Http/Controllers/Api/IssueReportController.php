<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{ActivityLog,IssueReport};
use Illuminate\Http\Request;
class IssueReportController extends Controller {
    public function index(Request $r){$q=IssueReport::with(['reporter:id,name','assignee:id,name']);if(!$r->user()->isAdmin())$q->where('reported_by',$r->user()->id);foreach(['priority','status','reported_by','assigned_to'] as $f)if($r->$f)$q->where($f,$r->$f);return response()->json($q->latest()->paginate(max(1,min((int) $r->input('per_page',10),50))));}
    public function store(Request $r){$data=$r->validate(['title'=>'required|string|max:150','description'=>'required|string|max:3000','priority'=>'required|in:Low,Medium,High,Urgent']);$data['reported_by']=$r->user()->id;$i=IssueReport::create($data);ActivityLog::record($r->user(),'create',$i,"Reported issue #{$i->id}.");return response()->json(['data'=>$i,'message'=>'Issue report submitted successfully.'],201);}
    public function show(Request $r,IssueReport $issueReport){if(!$r->user()->isAdmin()&&$issueReport->reported_by!==$r->user()->id)abort(403);return response()->json(['data'=>$issueReport->load('reporter:id,name','assignee:id,name')]);}
    public function update(Request $r,IssueReport $issueReport){if(!$r->user()->isAdmin()&&$issueReport->reported_by!==$r->user()->id)abort(403);$rules=$r->user()->isAdmin()?['title'=>'sometimes|string|max:150','description'=>'sometimes|string|max:3000','priority'=>'sometimes|in:Low,Medium,High,Urgent','status'=>'sometimes|in:Open,In Progress,Resolved,Closed','assigned_to'=>'nullable|exists:users,id','resolution_notes'=>'nullable|string|max:3000']:['title'=>'sometimes|string|max:150','description'=>'sometimes|string|max:3000','priority'=>'sometimes|in:Low,Medium,High,Urgent'];$data=$r->validate($rules);if(isset($data['status'])&&$data['status']==='Resolved')$data['resolved_at']=now();$issueReport->update($data);ActivityLog::record($r->user(),'update',$issueReport,"Updated issue #{$issueReport->id}.");return response()->json(['data'=>$issueReport->load('reporter:id,name','assignee:id,name'),'message'=>'Issue report updated successfully.']);}
}
