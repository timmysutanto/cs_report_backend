<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOffset extends Model
{
    use HasFactory; 

    public $timestamps = false;

    protected $table = 'user_offset';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_name', 
        'topic', 
        'group', 
        'offset',
        'status',
        'created_by', 
        'created_date', 
        'updated_by', 
        'updated_date'
    ];

    protected $hidden = [
    ];
}
