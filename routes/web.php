<?php

use Illuminate\Support\Facades\Route;

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
    //return view('welcome');
    return redirect()->route('auth.login');
});

\Ajifatur\Helpers\RouteExt::auth();
\Ajifatur\Helpers\RouteExt::admin();


Route::get('/cacheclear', function () {
    Artisan::call('cache:clear');
});
Route::get('/routecache', function () {
    Artisan::call('route:cache');
});
Route::get('/optimize', function () {
    Artisan::call('optimize');
});
Route::get('/routeclear', function () {
    Artisan::call('route:clear');
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});


// Guest Routes
Route::group(['middleware' => ['faturhelper.guest']], function() {
	Route::get('/login', function() {
		return view('auth/login');
	})->name('auth.login');
    Route::post('/login', 'LoginController@authenticate');
});

// Admin Routes
Route::group(['middleware' => ['faturhelper.admin']], function() {
    // Dashboard
    Route::get('/admin', 'DashboardController@index')->name('admin.dashboard');

    // Cabang
    Route::get('/admin/cabang', 'CabangController@index')->name('admin.cabang.index');
    Route::get('/admin/cabang/create', 'CabangController@create')->name('admin.cabang.create');
    Route::post('/admin/cabang/store', 'CabangController@store')->name('admin.cabang.store');
    Route::get('/admin/cabang/edit/{id}', 'CabangController@edit')->name('admin.cabang.edit');
    Route::post('/admin/cabang/update', 'CabangController@update')->name('admin.cabang.update');
    Route::post('/admin/cabang/delete', 'CabangController@delete')->name('admin.cabang.delete');

    // ATM
    Route::get('/admin/atm', 'ATMController@index')->name('admin.atm.index');
    Route::get('/admin/atm/create', 'ATMController@create')->name('admin.atm.create');
    Route::post('/admin/atm/store', 'ATMController@store')->name('admin.atm.store');
    Route::get('/admin/atm/edit/{id}', 'ATMController@edit')->name('admin.atm.edit');
    Route::post('/admin/atm/update', 'ATMController@update')->name('admin.atm.update');
    Route::post('/admin/atm/delete', 'ATMController@delete')->name('admin.atm.delete');
    Route::get('/admin/atm/import', 'ATMController@import')->name('admin.atm.import');

    // Vendor
    Route::get('/admin/vendor', 'VendorController@index')->name('admin.vendor.index');
    Route::get('/admin/vendor/create', 'VendorController@create')->name('admin.vendor.create');
    Route::post('/admin/vendor/store', 'VendorController@store')->name('admin.vendor.store');
    Route::get('/admin/vendor/edit/{id}', 'VendorController@edit')->name('admin.vendor.edit');
    Route::post('/admin/vendor/update', 'VendorController@update')->name('admin.vendor.update');
    Route::post('/admin/vendor/delete', 'VendorController@delete')->name('admin.vendor.delete');
    Route::post('/admin/vendor/change', 'VendorController@change')->name('admin.vendor.change');

    // Kategori
    Route::get('/admin/kategori', 'KategoriController@index')->name('admin.kategori.index');
    Route::get('/admin/kategori/create', 'KategoriController@create')->name('admin.kategori.create');
    Route::post('/admin/kategori/store', 'KategoriController@store')->name('admin.kategori.store');
    Route::get('/admin/kategori/edit/{id}', 'KategoriController@edit')->name('admin.kategori.edit');
    Route::post('/admin/kategori/update', 'KategoriController@update')->name('admin.kategori.update');
    Route::post('/admin/kategori/delete', 'KategoriController@delete')->name('admin.kategori.delete');
    Route::post('/admin/kategori/change', 'KategoriController@change')->name('admin.kategori.change');

    // Jenis Kategori
    Route::get('/admin/jenis', 'JenisController@index')->name('admin.jenis.index');
    Route::get('/admin/jenis/create', 'JenisController@create')->name('admin.jenis.create');
    Route::post('/admin/jenis/store', 'JenisController@store')->name('admin.jenis.store');
    Route::get('/admin/jenis/edit/{id}', 'JenisController@edit')->name('admin.jenis.edit');
    Route::post('/admin/jenis/update', 'JenisController@update')->name('admin.jenis.update');
    Route::post('/admin/jenis/delete', 'JenisController@delete')->name('admin.jenis.delete');

    // Admin Cabang
    Route::get('/admin/admin-cabang', 'AdminCabangController@index')->name('admin.admin-cabang.index');
    Route::get('/admin/admin-cabang/create', 'AdminCabangController@create')->name('admin.admin-cabang.create');
    Route::post('/admin/admin-cabang/store', 'AdminCabangController@store')->name('admin.admin-cabang.store');
    Route::get('/admin/admin-cabang/edit/{id}', 'AdminCabangController@edit')->name('admin.admin-cabang.edit');
    Route::post('/admin/admin-cabang/update', 'AdminCabangController@update')->name('admin.admin-cabang.update');
    Route::post('/admin/admin-cabang/delete', 'AdminCabangController@delete')->name('admin.admin-cabang.delete');

    // Admin Vendor
    Route::get('/admin/admin-vendor', 'AdminVendorController@index')->name('admin.admin-vendor.index');
    Route::get('/admin/admin-vendor/create', 'AdminVendorController@create')->name('admin.admin-vendor.create');
    Route::post('/admin/admin-vendor/store', 'AdminVendorController@store')->name('admin.admin-vendor.store');
    Route::get('/admin/admin-vendor/edit/{id}', 'AdminVendorController@edit')->name('admin.admin-vendor.edit');
    Route::post('/admin/admin-vendor/update', 'AdminVendorController@update')->name('admin.admin-vendor.update');
    Route::post('/admin/admin-vendor/delete', 'AdminVendorController@delete')->name('admin.admin-vendor.delete');

    // Pegawai
    Route::get('/admin/pegawai', 'PegawaiController@index')->name('admin.pegawai.index');
    Route::get('/admin/pegawai/create', 'PegawaiController@create')->name('admin.pegawai.create');
    Route::post('/admin/pegawai/store', 'PegawaiController@store')->name('admin.pegawai.store');
    Route::get('/admin/pegawai/edit/{id}', 'PegawaiController@edit')->name('admin.pegawai.edit');
    Route::post('/admin/pegawai/update', 'PegawaiController@update')->name('admin.pegawai.update');
    Route::post('/admin/pegawai/delete', 'PegawaiController@delete')->name('admin.pegawai.delete');
    Route::post('/admin/pegawai/delete-bulk', 'PegawaiController@deleteBulk')->name('admin.pegawai.delete-bulk');

    // Petugas
    Route::get('/admin/petugas', 'PetugasController@index')->name('admin.petugas.index');
    Route::get('/admin/petugas/create', 'PetugasController@create')->name('admin.petugas.create');
    Route::post('/admin/petugas/store', 'PetugasController@store')->name('admin.petugas.store');
    Route::get('/admin/petugas/edit/{id}', 'PetugasController@edit')->name('admin.petugas.edit');
    Route::post('/admin/petugas/update', 'PetugasController@update')->name('admin.petugas.update');
    Route::post('/admin/petugas/delete', 'PetugasController@delete')->name('admin.petugas.delete');
    Route::post('/admin/petugas/delete-bulk', 'PetugasController@deleteBulk')->name('admin.petugas.delete-bulk');

    // Monitoring
    Route::get('/admin/monitoring/create', 'MonitoringController@create')->name('admin.monitoring.create');
    Route::post('/admin/monitoring/store', 'MonitoringController@store')->name('admin.monitoring.store');
    Route::get('/admin/monitoring/detail/{id}', 'MonitoringController@detail')->name('admin.monitoring.detail');
    Route::get('/admin/monitoring/edit/{id}', 'MonitoringController@edit')->name('admin.monitoring.edit');
    Route::post('/admin/monitoring/update', 'MonitoringController@update')->name('admin.monitoring.update');
    Route::get('/admin/monitoring/category/{id}', 'MonitoringController@category')->name('admin.monitoring.category');
    Route::get('/admin/monitoring/amount/{id}', 'MonitoringController@amount')->name('admin.monitoring.amount');
    Route::post('/admin/monitoring/delete', 'MonitoringController@delete')->name('admin.monitoring.delete');
    Route::post('/admin/monitoring/delete-bulk', 'MonitoringController@deleteBulk')->name('admin.monitoring.delete-bulk');

    Route::prefix('monitoring')->group(function(){
        Route::get('/{cabang?}/{role?}', 'MonitoringController@index')->name('monitoring');
    });
    Route::prefix('daftarmonitoring')->group(function(){
        Route::get('/{id_user?}/{status?}', 'DataDebiturController@daftarmonitoring')->name('daftarmonitoring');
    });

    Route::prefix('DataSol')->group(function(){
        Route::get('/{start?}/{end?}/{status?}/{cabang?}', 'DataDebiturController@index')->name('DataSol');
    });

    Route::prefix('DataPros')->group(function(){
        Route::get('/{start?}/{end?}/{status?}/{cabang?}', 'DataDebiturController@DataPros')->name('DataPros');
        Route::post('/prospekdata', 'DataDebiturController@prospekdata')->name('prospekdata');
    });

    Route::prefix('DataPipe')->group(function(){
        Route::get('/{start?}/{end?}/{status?}/{cabang?}', 'DataDebiturController@DataPipe')->name('DataPipe');
        Route::post('/pipelinedata', 'DataDebiturController@pipelinedata')->name('pipelinedata');
    });

    Route::prefix('CloseDeb')->group(function(){
        Route::get('/{start?}/{end?}/{status?}/{cabang?}', 'DataDebiturController@CloseDeb')->name('CloseDeb');
    });
    Route::prefix('RejectDeb')->group(function(){
        Route::get('/{start?}/{end?}/{status?}/{cabang?}', 'DataDebiturController@RejectDeb')->name('RejectDeb');
    });

    Route::prefix('solicit')->group(function(){
        Route::post('/GetDataByCodePos', 'DataDebiturController@GetDataByCodePos');

        Route::post('/solicitdelete', 'DataDebiturController@delete')->name('solicitdelete');
        Route::post('/solicitdeleteall', 'DataDebiturController@solicitdeleteall')->name('solicitdeleteall');
        Route::post('/solicitdenyall', 'DataDebiturController@solicitdenyall')->name('solicitdenyall');
        Route::post('/solicitdeny', 'DataDebiturController@solicitdeny')->name('solicitdeny');

        Route::get('/solicitcreate', 'DataDebiturController@create')->name('solicitcreate');
        Route::post('/solicitstore', 'DataDebiturController@store')->name('solicitstore');
        Route::get('/solicitedit/{id?}', 'DataDebiturController@edit')->name('solicitedit');
        Route::post('/solicitupdate', 'DataDebiturController@update')->name('solicitupdate');
        Route::post('/verifisolicit', 'DataDebiturController@verifisolicit')->name('verifisolicit');
        Route::post('/solicitverifall', 'DataDebiturController@solicitverifall')->name('solicitverifall');

        Route::post('/appsolicit', 'DataDebiturController@appsolicit')->name('appsolicit');
        Route::post('/solicitappall', 'DataDebiturController@solicitappall')->name('solicitappall');

    });

    Route::prefix('datadebdetail')->group(function(){
        Route::get('/{id?}', 'DataDebiturController@detail')->name('datadebdetail');
    });

    Route::get('/openfile/{path?}/{name?}', 'DataDebiturController@openfile')->name('openfile');

    Route::prefix('master')->group(function(){
        Route::get('/picmonitoring', 'MstUserController@index')->name('picmonitoring');
        Route::get('/picapproval', 'MstUserController@index')->name('picapproval');
        Route::get('/picverifikator', 'MstUserController@index')->name('picverifikator');
        Route::get('/picinputer', 'MstUserController@index')->name('picinputer');
        Route::get('/picadmin', 'MstUserController@index')->name('picadmin');

        Route::post('/picdelete', 'MstUserController@delete')->name('picdelete');
        Route::get('/piccreate/{roles?}', 'MstUserController@create')->name('piccreate');
        Route::post('/picstore', 'MstUserController@store')->name('picstore');
        Route::get('/picedit/{id?}', 'MstUserController@edit')->name('picedit');
        Route::post('/picupdate', 'MstUserController@update')->name('picupdate');

        Route::get('/sektor', 'MstSektorController@index')->name('sektor');
        Route::post('/sektordelete', 'MstSektorController@delete')->name('sektordelete');
        Route::get('/sektorcreate', 'MstSektorController@create')->name('sektorcreate');
        Route::post('/sektorstore', 'MstSektorController@store')->name('sektorstore');
        Route::get('/sektoredit/{id?}', 'MstSektorController@edit')->name('sektoredit');
        Route::post('/sektorupdate', 'MstSektorController@update')->name('sektorupdate');

        Route::get('/sumber', 'MstSumberController@index')->name('sumber');
        Route::post('/sumberdelete', 'MstSumberController@delete')->name('sumberdelete');
        Route::get('/sumbercreate', 'MstSumberController@create')->name('sumbercreate');
        Route::post('/sumberstore', 'MstSumberController@store')->name('sumberstore');
        Route::get('/sumberedit/{id?}', 'MstSumberController@edit')->name('sumberedit');
        Route::post('/sumberupdate', 'MstSumberController@update')->name('sumberupdate');

        Route::get('/pengumuman/{ed?}', 'PengumumanController@index')->name('pengumuman');
        Route::post('/pengumumandelete', 'PengumumanController@delete')->name('pengumumandelete');
        Route::get('/pengumumancreate', 'PengumumanController@create')->name('pengumumancreate');
        Route::post('/pengumumanuploadimg', 'PengumumanController@pengumumanuploadimg')->name('pengumumanuploadimg');
        Route::post('/pengumumanstore', 'PengumumanController@store')->name('pengumumanstore');
        Route::get('/pengumumanedit/{id?}', 'PengumumanController@edit')->name('pengumumanedit');
        Route::post('/pengumumanupdate', 'PengumumanController@update')->name('pengumumanupdate');
    });

    // Users Settings
    Route::get('/admin/profile', 'UserSettingController@index')->name('admin.profile');
    Route::get('/admin/settings/profile', 'UserSettingController@profile')->name('admin.settings.profile');
    Route::post('/admin/settings/profile/update', 'UserSettingController@updateProfile')->name('admin.settings.profile.update');
    Route::get('/admin/settings/account', 'UserSettingController@account')->name('admin.settings.account');
    Route::post('/admin/settings/account/update', 'UserSettingController@updateAccount')->name('admin.settings.account.update');
    Route::get('/admin/settings/password', 'UserSettingController@password')->name('admin.settings.password');
    Route::post('/admin/settings/password/update', 'UserSettingController@updatePassword')->name('admin.settings.password.update');
    Route::get('/admin/settings/avatar', 'UserSettingController@avatar')->name('admin.settings.avatar');
    Route::post('/admin/settings/avatar/update', 'UserSettingController@updateAvatar')->name('admin.settings.avatar.update');
});
