<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RolePermissionsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:view-dashboard');

    Route::get('profile', [UserProfileController::class, 'index'])->name('profile')->middleware('permission:view-users');
    Route::post('profile/update', [UserProfileController::class, 'update'])->name('profile.update')->middleware('permission:create-users');
    Route::post('profile/update-password', [UserProfileController::class, 'updatePassword'])->name('profile.update-password')->middleware('permission:create-users');

    Route::get('users', [UserController::class, 'index'])->name('users')->middleware('permission:view-users');
    Route::get('get-roles', [UserController::class, 'getRoles'])->name('get-roles')->middleware('permission:create-users');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-users');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:create-users');
    Route::put('users/{id}/update', [UserController::class, 'update'])->name('users.update')->middleware('permission:create-users');

    Route::get('roles', [RolePermissionsController::class, 'index'])->name('roles')->middleware('permission:view-roles');
    Route::get('get-permissions', [RolePermissionsController::class, 'getPermissions'])->name('get-permissions')->middleware('permission:create-roles');
    Route::post('roles', [RolePermissionsController::class, 'store'])->name('roles.store')->middleware('permission:create-roles');
    Route::get('roles/{id}/edit', [RolePermissionsController::class, 'edit'])->name('roles.edit')->middleware('permission:edit-roles');
    Route::put('roles/{id}', [RolePermissionsController::class, 'update'])->name('roles.update')->middleware('permission:edit-roles');
    Route::delete('/roles/{id}', [RolePermissionsController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete-roles');

});

require __DIR__ . '/auth.php';
