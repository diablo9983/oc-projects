<?php namespace BootstrapHunter\Projects\Models;

use Model;

class Task extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['created_at','updated_at', 'due_date'];

    public $rules = [
      'name'        => 'required'
    ];

    public $hasOne = [
      'user' => [
        'Backend\Models\User',
        'key' => 'id',
        'otherKey' => 'user_id'
      ]
    ];

    public $table = 'bootstraphunter_projects_tasks';

    public function scopeActive($query) {
        return $query->where('status', 0);
    }

    public function scopeCompleted($query) {
        return $query->where('status', 1);
    }

    public function scopeArchived($query, bool $archive = NULL) {
        return $query->where('archived', $archive ? '=' : '!=', 1);
    }
}
