<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
  public $timestamps  = false;

  protected $table = 'project_user';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
       'user_id', 'project_id', 'user_role'
  ];
}