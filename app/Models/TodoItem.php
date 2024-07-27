<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'is_completed', 'todo_group_id'];

    public function todoGroup()
    {
        return $this->belongsTo(TodoGroup::class);
    }
}
