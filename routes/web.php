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
    Route::get('/dashboard/{tahun?}/{start?}/{end?}/{cabang?}/{tim?}/{role?}', 'DashboardController@index')->name('admin.dashboard');

    Route::get('/printdata/{id?}', 'DataDebiturController@printdata')->name('printdata');

    Route::prefix('notifikasi')->group(function(){
        Route::get('/notifikasi', 'NotifikasiController@index')->name('notifikasi');
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
        Route::post('/appprospek', 'DataDebiturController@appprospek')->name('appprospek');
        Route::post('/prospectappall', 'DataDebiturController@prospectappall')->name('prospectappall');
    });

    Route::prefix('MasterData')->group(function(){
        Route::get('/{start?}/{end?}/{status?}/{cabang?}', 'DataDebiturController@MasterData')->name('MasterData');
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
        Route::get('/', 'MstUserController@index')->name('master');

        Route::get('/jabatan', 'MstJabatanController@index')->name('jabatan');
        Route::post('/jabatandelete', 'MstJabatanController@delete')->name('jabatandelete');
        Route::get('/jabatancreate', 'MstJabatanController@create')->name('jabatancreate');
        Route::post('/jabatanstore', 'MstJabatanController@store')->name('jabatanstore');
        Route::get('/jabatanedit/{id?}', 'MstJabatanController@edit')->name('jabatanedit');
        Route::post('/jabatanupdate', 'MstJabatanController@update')->name('jabatanupdate');

        Route::get('/cabang', 'MstCabangController@index')->name('cabang');
        Route::post('/cabangdelete', 'MstCabangController@delete')->name('cabangdelete');
        Route::get('/cabangcreate', 'MstCabangController@create')->name('cabangcreate');
        Route::post('/cabangstore', 'MstCabangController@store')->name('cabangstore');
        Route::get('/cabangedit/{id?}', 'MstCabangController@edit')->name('cabangedit');
        Route::post('/cabangupdate', 'MstCabangController@update')->name('cabangupdate');

        Route::get('/tim', 'MstTimController@index')->name('tim');
        Route::post('/timdelete', 'MstTimController@delete')->name('timdelete');
        Route::get('/timcreate', 'MstTimController@create')->name('timcreate');
        Route::post('/timstore', 'MstTimController@store')->name('timstore');
        Route::get('/timedit/{id?}', 'MstTimController@edit')->name('timedit');
        Route::post('/timupdate', 'MstTimController@update')->name('timupdate');

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

        Route::get('/skim', 'MstSkimController@index')->name('skim');
        Route::post('/skimdelete', 'MstSkimController@delete')->name('skimdelete');
        Route::get('/skimcreate', 'MstSkimController@create')->name('skimcreate');
        Route::post('/skimstore', 'MstSkimController@store')->name('skimstore');
        Route::get('/skimedit/{id?}', 'MstSkimController@edit')->name('skimedit');
        Route::post('/skimupdate', 'MstSkimController@update')->name('skimupdate');

        Route::get('/fasilitas', 'MstFasilitasController@index')->name('fasilitas');
        Route::post('/fasilitasdelete', 'MstFasilitasController@delete')->name('fasilitasdelete');
        Route::get('/fasilitascreate', 'MstFasilitasController@create')->name('fasilitascreate');
        Route::post('/fasilitasstore', 'MstFasilitasController@store')->name('fasilitasstore');
        Route::get('/fasilitasedit/{id?}', 'MstFasilitasController@edit')->name('fasilitasedit');
        Route::post('/fasilitasupdate', 'MstFasilitasController@update')->name('fasilitasupdate');

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
