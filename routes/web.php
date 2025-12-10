<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\ProjectManagement::class, 'index'])->name('project.list');


// Route for project
Route::post('/project-create', [App\Http\Controllers\ProjectManagement::class, 'create'])->name('project.create');
Route::post('/project-update', [App\Http\Controllers\ProjectManagement::class, 'update'])->name('project.update');
Route::get('/project-delete/{id}', [App\Http\Controllers\ProjectManagement::class, 'delete'])->name('project.remove');
Route::get('/project-detail/{id}', [App\Http\Controllers\ProjectManagement::class, 'detail'])->name('project.detail');

// Route for task
Route::post('/task-create', [App\Http\Controllers\TaskManagement::class, 'create'])->name('task.create');
Route::post('/task-update', [App\Http\Controllers\TaskManagement::class, 'update'])->name('task.update');
Route::get('/task-delete/{id}', [App\Http\Controllers\TaskManagement::class, 'delete'])->name('task.delete');
Route::post('/task-change-status', [App\Http\Controllers\TaskManagement::class, 'change_status'])->name('task.change.status');
