<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\InspecController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\saleController;
use Illuminate\Support\Facades\Response;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login',[AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'login_func']);
Route::get('/admin/logout', [AuthController::class, 'logout_admin'])->name('admin.logout');
Route::get('/privacy',[AdminController::class, 'privacy']);

// Route::middleware('auth:sanctum', 'admin')->group(function () {
//     // เข้าถึงโดยเฉพาะ admin
// });
route::group(['middleware'=>['checklogin']],function(){
    Route::get('/',[AdminController::class, 'dashboard']);
    Route::get('/dashboard/search',[AdminController::class, 'dashboard_search'])->name('dashboard.search');
    // Route::get('/dashboard/search', [AdminController::class, 'dashboard_search'])->name('search.get');

    Route::POST('/get_jobtype',[AdminController::class, 'get_jobtype']);
    Route::get('/download/apk', function () {
        $file = public_path('APK/app-release.zip');

        if (file_exists($file)) {
            return response()->download($file, 'Syslink-Technology.rar'); // เปลี่ยนชื่อไฟล์ตามต้องการ
        } else {
            abort(404, 'File not found.');
        }
    });
    //admin

    Route::prefix('admin')->group(function () {
        Route::get('',[AdminController::class, 'index']);
        Route::get('add',[AdminController::class,'add']);
        Route::post('add/sub',[AdminController::class,'addsub'])->name('admin.addsub');
        Route::get('edit/{id}',[AdminController::class,'edit']);
        Route::post('edit/sub',[AdminController::class,'editsub'])->name('admin.del');
        Route::post('delete',[AdminController::class,'delete_admin']);
    });

    // Route::get('/admin',[AdminController::class, 'index']);
    // Route::get('/admin/add',[AdminController::class,'add']);
    // Route::post('/admin/add/sub',[AdminController::class,'addsub'])->name('admin.addsub');
    // Route::get('/admin/edit/{id}',[AdminController::class,'edit']);
    // Route::post('/admin/edit/sub',[AdminController::class,'editsub'])->name('admin.del');
    // Route::post('/admin/delete',[AdminController::class,'delete_admin']);

    //technician
    Route::get('/technician',[EngineerController::class, 'index']);
    Route::get('/technician/add',[EngineerController::class,'add']);
    Route::post('/technician/add/sub',[EngineerController::class,'addsub'])->name('technician.addsub');
    Route::get('/technician/edit/{id}',[EngineerController::class,'edit']);
    Route::post('/technician/edit/sub',[EngineerController::class,'editsub']);
    Route::post('/technician/delete',[EngineerController::class,'delete_engineer']);

    //engineer
    Route::get('/engineer',[InspecController::class, 'index']);
    Route::get('/engineer/add',[InspecController::class,'add']);
    Route::post('/engineer/add/sub',[InspecController::class,'addsub'])->name('engineer.addsub');
    Route::get('/engineer/edit/{id}',[InspecController::class,'edit']);
    Route::post('/engineer/edit/sub',[InspecController::class,'editsub'])->name('engineer.del');
    Route::post('/engineer/delete',[InspecController::class,'delete']);

    //sale
    Route::get('/sale',[saleController::class, 'index']);
    Route::get('/sale/add',[saleController::class,'add']);
    Route::post('/sale/add/sub',[saleController::class,'addsub'])->name('sale.addsub');
    Route::get('/sale/edit/{id}',[saleController::class,'edit']);
    Route::post('/sale/edit/sub',[saleController::class,'editsub'])->name('sale.del');
    Route::post('/sale/delete',[saleController::class,'delete']);

    //job
    Route::prefix('job')->group(function () {
        Route::get('',[JobController::class, 'index']);
        Route::get('add',[JobController::class,'add']);
        Route::post('add/sub',[JobController::class,'addsub']);
        Route::get('edit/{id}',[JobController::class,'edit']);
        Route::post('edit/sub',[JobController::class,'editsub']);
        Route::post('delete',[JobController::class,'delete_job']);
        Route::post('file/delete',[JobController::class,'delete_file']);
    });

    Route::prefix('product')->group(function () {
        Route::get('',[ProductController::class, 'index']);
        Route::post('add',[ProductController::class,'create'])->name('product.add');
        Route::get('edit/{id}',[ProductController::class,'edit'])->name('product.edit');
        Route::post('update',[ProductController::class,'update'])->name('product.update');
        Route::post('delete',[ProductController::class,'delete_product']);
        Route::post('file/delete',[ProductController::class,'delete_file']);
    });

    //team
    Route::get('/team',[TeamController::class, 'index']);
    Route::get('/team/add',[TeamController::class,'add']);
    Route::post('/team/add/sub',[TeamController::class,'addsub']);
    Route::get('/team/edit/{id}',[TeamController::class,'edit']);
    Route::post('/team/edit/sub',[TeamController::class,'editsub']);
    Route::post('/team/delete',[TeamController::class,'delete']);
    Route::post('/team/delete/user',[TeamController::class,'delete_user']);
    Route::get('/team/list/{id}',[TeamController::class,'list']);
    Route::post('/team/search',[TeamController::class,'search'])->name('team.search');
    // Route::post('/team/file/delete',[TeamController::class,'delete_file']);

    //project
    Route::get('/project',[ProjectController::class,'index'])->name('project');
    Route::get('/project/submit/{id}',[ProjectController::class,'add_submit'])->name('project.add');
    Route::post('/project/create',[ProjectController::class,'create'])->name('project.create');
    Route::post('/project/submit/create',[ProjectController::class,'submit_create'])->name('project.submit_create');
    Route::get('/project/edit/{id}',[ProjectController::class,'edit'])->name('project.edit');
    Route::get('/project/submit/edit/{id}',[ProjectController::class,'submit_edit'])->name('project.submit_edit');
    Route::post('/project/update',[ProjectController::class,'update'])->name('project.update');
    Route::post('/project/submit/update',[ProjectController::class,'submit_update'])->name('project.submit_update');
    Route::post('/project/delete',[ProjectController::class,'delete'])->name('project.delete');
    Route::post('/project/submit/delete',[ProjectController::class,'submit_delete'])->name('project.submit_delete');
    Route::post('/project/search',[ProjectController::class,'search'])->name('project.search');
    //export
    Route::get('/export/job/pdf',[ExportController::class,'export_submit']);
    Route::post('/export/excel/team_report',[ExportController::class,'team_report']);
    Route::post('/export/excel/project_report',[ExportController::class,'project_report']);

    //report
    Route::get('/job/submit_work/{id}   ',[ExportController::class,'view_submit']);
    Route::get('/album/admin/{id}   ',[ExportController::class,'album_admin']);
    Route::get('/album/sale/{id}   ',[ExportController::class,'album_sale']);
    Route::get('/album/engineer/{id}   ',[ExportController::class,'album_engineer']);
    Route::get('/districts/{province_id}', [JobController::class, 'getDistricts']);

    //search
    Route::get('/search', [AdminController::class, 'search'])->name('search');


    Route::get('/clear-cache', function() {
        $exitCode = Artisan::call('cache:clear');
        return '<h1>Cache facade value cleared</h1>';
      });
});






