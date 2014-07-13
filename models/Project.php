<?php 
/**
 * Created by ShahiemSeymor.
 * Date: 6/19/14
 */
namespace ShahiemSeymor\Todo\Models;
use Model;
use BackendAuth;

class Project extends Model
{

    use \October\Rain\Database\Traits\Validation;

    public $table = 'shahiemseymor_todo_projects'; 
	protected $fillable = ['title', 'description'];
	protected $purgeable = ['assign'];
	public $hasMany = [
        'todo' => ['ShahiemSeymor\Todo\Models\Todo']
    ];	

    public $belongsTo = [
        'Project' => ['Backend\Models\User', 'foreignKey' => 'user_id']
    ];

    public $rules = [
        'title'                  => 'required',
    ];

    public function beforeCreate()
	{
	    $this->user_id = BackendAuth::getUser()->id;
	}
  
}