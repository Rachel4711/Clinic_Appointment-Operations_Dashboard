<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
class ActivityLogController extends Controller {
    public function index(Request $r){$q=ActivityLog::with('user:id,name,email');foreach(['user_id','action','entity_type'] as $f)if($r->$f)$q->where($f,$r->$f);return response()->json($q->latest()->paginate(max(1,min((int) $r->input('per_page',20),100))));}
}
