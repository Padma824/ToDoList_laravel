<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function todoItems()
    {
        return $this->hasMany(TodoItem::class);
    }
}
