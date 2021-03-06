<?php 
namespace ShahiemSeymor\Todo\Models;

use BackendAuth;
use Model;
use ShahiemSeymor\Todo\Models\Assign;

class Project extends Model
{
    use \October\Rain\Database\Traits\Purgeable;
    use \October\Rain\Database\Traits\Validation;
    
    public $table         = 'shahiemseymor_todo_projects'; 

	protected $fillable   = ['title', 'description'];
	protected $purgeable  = ['assign'];

	public $hasMany       = ['todo'     => ['ShahiemSeymor\Todo\Models\Todo']];	
    public $belongsTo     = ['project'  => ['Backend\Models\User', 'key' => 'user_id']];
    public $rules         = ['title'    => 'required'];

    public function beforeCreate()
	{
	    $this->user_id = BackendAuth::getUser()->id;
	} 

	public function afterCreate()
	{
        $this->save();

        $assign = new static;
        $assign->assignAdministrators($this->id);
	}

	public function afterSave()
	{
        $deleteModel = Assign::where('project_id', $this->id);

        if($deleteModel->count() >= 1)
            $deleteModel->delete();

        $assign = new static;
       	$assign->assignAdministrators($this->id);
	}
  	
  	public function getCreatorAttribute()
    {
    	return $this->project->first_name.' '.$this->project->last_name;
    }

    public function assignAdministrators($recordId)
    {
    	if(post('Project[assign]') != '')
        {
            $administratorsList = explode(",", post('Project[assign]'));
            foreach($administratorsList as $administratorsAssigned)
            {
                $assign             = new Assign;
                $assign->user_id    = $administratorsAssigned;
                $assign->project_id = $recordId;
                $assign->save();
            }
        }
    }
}