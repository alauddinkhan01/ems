<?php

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tasks\TasksController;
use App\Http\Controllers\Employes\EmployesController;
use App\Http\Controllers\Attandance\AttandanceController;
use App\Http\Controllers\Departments\DepartmentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $userLatestAttendance = User::with('latest_attendance')->where('id', auth()->user()->id)->first();
    // dd($userLatestAttendance);
    $currentDate = Carbon::now();
    return Inertia::render('NewDashboard',compact('userLatestAttendance','currentDate'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// depatments
Route::group([
    
    "prefix" => "Departments",
], function () {
    Route::get('/index',[DepartmentsController::class,'index'])->name('departments');
    Route::get('/create',[DepartmentsController::class,'create'])->name('create.department');
    Route::post('/sotre',[DepartmentsController::class,'store'])->name('store.department');
    Route::post('/update',[DepartmentsController::class,'updateField'])->name('update.department');
    Route::delete('/delete/{id}',[DepartmentsController::class,'destroy'])->name('delete.department');
});
// employes
Route::group([
    
    "prefix" => "employes",
], function () {
    Route::get('/index',[EmployesController::class,'index'])->name('employes');
    Route::post('/store',[EmployesController::class,'store'])->name('store.employe');
    Route::post('/update',[EmployesController::class,'update'])->name('update.employe');
    Route::delete('/delete/{id}',[EmployesController::class,'destroy'])->name('delete.employe');

});

// Tasks
Route::group([
    'prefix'=> "tasks"
], function(){
    Route::get('index/{id}',[TasksController::class,'index'])->name('tasks');
    Route::post('assign',[TasksController::class,'assignTask'])->name('assign.task');
    Route::post('update',[TasksController::class,'updateTask'])->name('update.task');
    Route::get('delete/{id}',[TasksController::class,'deleteTask'])->name('delete.task');
    Route::post('get-tasks',[TasksController::class,'getTasks'])->name('get.tasks');
    Route::get('my-tasks',[TasksController::class,'myTasks'])->name('my.tasks');
    Route::post('change-tasks/{id}',[TasksController::class,'changeStatus'])->name('change.status');
});
// Attandance
Route::group([
    'prefix'=> "attandance"
], function(){
    Route::get('check-in',[AttandanceController::class,'checkIn'])->name('check-in');
    
});

require __DIR__.'/auth.php';
