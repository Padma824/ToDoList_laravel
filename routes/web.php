<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\TodoController;

Route::get('/', [GroupController::class, 'index'])->name('todos.index');
Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
Route::patch('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggleComplete'])->name('todos.toggle');
Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');


Route::resource('groups', GroupController::class);
Route::resource('todos', TodoController::class);