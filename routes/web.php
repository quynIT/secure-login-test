<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckPasswordChangeRequired;
use App\Http\Middleware\CheckRole;

// Đăng nhập / Đăng xuất
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Route riêng để đổi mật khẩu khi force_password_change = true
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [LoginController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [LoginController::class, 'changePassword'])->name('password.update');
    // Thêm routes cho trang troll
    Route::get('/admin-question', [LoginController::class, 'troll'])->name('admin.question');
    Route::post('/admin-answer', [LoginController::class, 'answerTroll'])->name('admin.answer');
});

// Khu vực yêu cầu đăng nhập và đã đổi mật khẩu
Route::middleware(['auth', CheckPasswordChangeRequired::class])->group(function () {
    // Route cho trang chủ - ai cũng vào được
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [App\Http\Controllers\EmployeeController::class, 'profile'])->name('employee.profile');
        Route::put('/profile/update', [App\Http\Controllers\EmployeeController::class, 'updateProfile'])->name('employee.updateProfile');
    });
    
    // Các route chỉ dành cho root
    Route::middleware([CheckRole::class.':root'])->group(function () {
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/', [UserController::class, 'store'])->name('users.store');
            Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        });
        
        Route::group(['prefix' => 'departments'], function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('departments.index');
            Route::post('/', [DepartmentController::class, 'store'])->name('departments.store');
            Route::get('/{id}', [DepartmentController::class, 'show'])->name('departments.show');
            Route::put('/{id}', [DepartmentController::class, 'update'])->name('departments.update');
            Route::delete('/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
        });
    });
});