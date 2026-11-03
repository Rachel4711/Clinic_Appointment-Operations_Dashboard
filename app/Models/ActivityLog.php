<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ActivityLog extends Model {
    protected $fillable = ['user_id','action','entity_type','entity_id','description'];
    public function user(){ return $this->belongsTo(User::class); }
    public static function record($user, $action, $model, $description){
        return static::create(['user_id'=>$user ? $user->id : null,'action'=>$action,'entity_type'=>class_basename($model),'entity_id'=>$model->id ?? null,'description'=>$description]);
    }
}
