<?php

namespace App\Http\Controllers;

use Auth;
use PDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonitoringExport;
use App\Exports\MonitoringDetailExport;
use App\Models\ATM;
use App\Models\Cabang;
use App\Models\Vendor;
use App\Models\Kategori;
use App\Models\Monitoring;
use App\Models\MonitoringDetail;
use App\Models\Tipe;
Use Image;
use Intervention\Image\Exception\NotReadableException;
use App\Models\User;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        $t1 = $request->query('t1') != null ? DateTimeExt::change($request->query('t1')) : date('Y-m-d');
        $t2 = $request->query('t2') != null ? DateTimeExt::change($request->query('t2')) : date('Y-m-d');
        $cabang = Cabang::orderBy('nama','asc')->get();
        $vendor = Vendor::orderBy('nama','asc')->get();

        // Get monitoring
        if(Auth::user()->role->is_global == 1) {
            if($request->query('cabang') != 0)
                $monitoring = Monitoring::has('user')->where('cabang_id','=',$request->query('cabang'))->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2)->orderBy('created_at','desc')->get();
            else
                $monitoring = Monitoring::has('user')->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2)->orderBy('created_at','desc')->get();
        }
        elseif(Auth::user()->role->is_global == 0) {
            if(Auth::user()->role_id == role('admin-cabang'))
                $monitoring = Monitoring::has('user')->where('cabang_id','=',Auth::user()->attribute->cabang_id)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2)->orderBy('created_at','desc')->get();
            elseif(Auth::user()->role_id == role('admin-vendor'))
                if($request->query('vendor') != 0)
                    $monitoring = Monitoring::has('user')->where('vendor_id','=',$request->query('vendor'))->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2)->orderBy('created_at','desc')->get();
                else
                    $monitoring = Monitoring::has('user')->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2)->orderBy('created_at','desc')->get();
        }

        // Export to PDF
        if($request->query('print') == 'pdf') {
            // Get the cabang
            $cabang = Cabang::find($request->query('cabang'));

            $pdf = PDF::loadview('admin/monitoring/pdf', [
                'monitoring' => $monitoring,
                'cabang' => $cabang,
                't1' => $t1,
                't2' => $t2,
            ])->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->stream('Laporan Monitoring ATM.pdf');
        }
        // Export to Excel
        elseif($request->query('print') == 'excel') {
            // Get the cabang
            $cabang = Cabang::find($request->query('cabang'));

            // Filename
            $filename = $cabang ? 'Laporan Monitoring ATM di '.$cabang->nama : 'Laporan Monitoring ATM';
			$filename = preg_replace("/[^a-zA-Z0-9_ -.,()]/s", '', $filename);

            return Excel::download(new MonitoringExport($monitoring), $filename.'.xlsx');
        }
        // View
        else {
            return view('admin/monitoring/index', [
                'monitoring' => $monitoring,
                'cabang' => $cabang,
                'vendor' => $vendor,
                't1' => $t1,
                't2' => $t2,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role_id == role('pegawai') || Auth::user()->role_id == role('petugas')) {
            // Get ATM
            $atm = ATM::where('cabang_id','=',Auth::user()->attribute->cabang_id)->orderBy('nama','asc')->get();

            // Get the tipe
            if(Auth::user()->role_id == role('petugas'))
                $tipe = Tipe::find(Auth::user()->attribute->tipe_id);
            elseif(Auth::user()->role_id == role('pegawai'))
                $tipe = Tipe::where('nama','=','Kebersihan')->first();

            // Get kategori
            $kategori_interior = Kategori::has('jenis')->whereIn('id',$tipe->kategori()->pluck('kategori_id')->toArray())->where('jenis_id','=',1)->orderBy('jenis_id','asc')->get();
            $kategori_eksterior = Kategori::has('jenis')->whereIn('id',$tipe->kategori()->pluck('kategori_id')->toArray())->where('jenis_id','=',2)->orderBy('jenis_id','asc')->get();

            // View
            return view('admin/monitoring/create', [
                'atm' => $atm,
                'kategori_interior' => $kategori_interior,
                'kategori_eksterior' => $kategori_eksterior
            ]);
        }
        else abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Upload and resize image
        $imgData = [];
        $images = $request->file('img');
        if($images != null){    
            foreach ($images as $key=>$image){
                
                $imageName = time().'-'.$key.'.'.$image->extension();
            
                $destinationPathThumbnail = public_path('assets/images/monitoring');
                $img = Image::make($image->path());
                $img->resize(500, 500, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPathThumbnail.'/'.$imageName);
            
                array_push($imgData,$imageName);
            }
        }
     
        // Validation
        $validator = Validator::make($request->all(), [
            'atm' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the monitoring
            $monitoring = new Monitoring;
            $monitoring->user_id = Auth::user()->id;
            $monitoring->vendor_id = Auth::user()->attribute->vendor_id;
            $monitoring->tipe_id = Auth::user()->attribute->tipe_id;
            $monitoring->cabang_id = Auth::user()->attribute->cabang_id;
            $monitoring->atm_id = $request->atm;
            $monitoring->longitude = $request->long;
            $monitoring->latitude = $request->lat;
            $monitoring->lokasi = $request->lokasi;
            $monitoring->catatan = $request->catatan;
            $monitoring->img = json_encode($imgData);
            $monitoring->save();

            // Save the monitoring detail
            foreach($request->get('radio') as $key=>$radio) {
                $monitoring_detail = new MonitoringDetail;
                $monitoring_detail->monitoring_id = $monitoring->id;
                $monitoring_detail->kategori_id = $key;
                $monitoring_detail->jawaban = $radio;
                $monitoring_detail->save();
            }

            // Redirect
            return redirect()->route('admin.dashboard')->with(['message' => 'Berhasil menambah data.', 'status' => 1]);
        }
    }

    /**
     * Show the detail of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        if(Auth::user()->role_id == role('super-admin') || Auth::user()->role_id == role('admin-wilayah') || Auth::user()->role_id == role('admin-cabang') || Auth::user()->role_id == role('admin-vendor')) {
            // Get the monitoring
            $monitoring = Monitoring::findOrFail($id);

            // Get the tipe
            if($monitoring->user->role_id == role('petugas'))
                $tipe = Tipe::find($monitoring->user->attribute->tipe_id);
            elseif($monitoring->user->role_id == role('pegawai'))
                $tipe = Tipe::where('nama','=','Kebersihan')->first();

            // Get kategori
            $kategori_interior = Kategori::has('jenis')->whereIn('id',$tipe->kategori()->pluck('kategori_id')->toArray())->where('jenis_id','=',1)->orderBy('jenis_id','asc')->get();
            $kategori_eksterior = Kategori::has('jenis')->whereIn('id',$tipe->kategori()->pluck('kategori_id')->toArray())->where('jenis_id','=',2)->orderBy('jenis_id','asc')->get();

            // View
            return view('admin/monitoring/detail', [
                'monitoring' =>$monitoring,
                'kategori_interior' => $kategori_interior,
                'kategori_eksterior' => $kategori_eksterior
            ]);
        }
        else abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        if(Auth::user()->role_id == role('super-admin') || Auth::user()->role_id == role('admin-wilayah') || Auth::user()->role_id == role('admin-cabang') || Auth::user()->role_id == role('admin-vendor')) {
            // Get the monitoring
            $monitoring = Monitoring::findOrFail($id);

            // Get ATM
            $atm = ATM::where('cabang_id','=',$monitoring->cabang_id)->orderBy('nama','asc')->get();

            // Get the tipe
            if($monitoring->user->role_id == role('petugas'))
                $tipe = Tipe::find($monitoring->user->attribute->tipe_id);
            elseif($monitoring->user->role_id == role('pegawai'))
                $tipe = Tipe::where('nama','=','Kebersihan')->first();

            // Get kategori
            $kategori_interior = Kategori::has('jenis')->whereIn('id',$tipe->kategori()->pluck('kategori_id')->toArray())->where('jenis_id','=',1)->orderBy('jenis_id','asc')->get();
            $kategori_eksterior = Kategori::has('jenis')->whereIn('id',$tipe->kategori()->pluck('kategori_id')->toArray())->where('jenis_id','=',2)->orderBy('jenis_id','asc')->get();

            // View
            return view('admin/monitoring/edit', [
                'monitoring' =>$monitoring,
                'atm' =>$atm,
                'kategori_interior' => $kategori_interior,
                'kategori_eksterior' => $kategori_eksterior
            ]);
        }
        else abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'atm' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the monitoring
            $monitoring = Monitoring::find($request->id);
            $monitoring->atm_id = $request->atm;
            $monitoring->catatan = $request->catatan;
            $monitoring->save();

            // Update the monitoring detail
            foreach($request->get('radio') as $key=>$radio) {
                $monitoring_detail = MonitoringDetail::where('monitoring_id','=',$monitoring->id)->where('kategori_id','=',$key)->first();
                $monitoring_detail->jawaban = $radio;
                $monitoring_detail->save();
            }

            // Redirect
            return redirect()->route('admin.monitoring.index')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the monitoring
        $monitoring = Monitoring::find($request->id);

        // Delete the monitoring
        $monitoring->delete();
        
        // Delete the monitoring detail
        $monitoring_detail = MonitoringDetail::where('monitoring_id','=',$monitoring->id)->delete();

        // Redirect
        return redirect()->route('admin.monitoring.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteBulk(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Explode ID
        $ids = explode(',', $request->ids);

        foreach($ids as $id) {
            // Get the monitoring
            $monitoring = Monitoring::find($id);
    
            // Delete the monitoring
            $monitoring->delete();
            
            // Delete the monitoring detail
            $monitoring_detail = MonitoringDetail::where('monitoring_id','=',$monitoring->id)->delete();
        }
        
        // Redirect
        return redirect()->route('admin.monitoring.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Show by category.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request, $id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        if(Auth::user()->role_id == role('super-admin') || Auth::user()->role_id == role('admin-wilayah') || Auth::user()->role_id == role('admin-cabang') || Auth::user()->role_id == role('admin-vendor')) {
            $t1 = $request->query('t1') != null ? DateTimeExt::change($request->query('t1')) : date('Y-m-d');
            $t2 = $request->query('t2') != null ? DateTimeExt::change($request->query('t2')) : date('Y-m-d');

            // Get the kategori
            $kategori = Kategori::findOrFail($id);
    
            // Get monitoring
            if(Auth::user()->role->is_global == 1) {
                $cabang = $request->query('cabang');
                if($cabang != 0)
                    $monitoring_detail = MonitoringDetail::has('kategori')->where('kategori_id','=',$id)->whereHas('monitoring', function (Builder $query) use ($cabang, $t1, $t2) {
                        return $query->has('user')->where('cabang_id','=',$cabang)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                    })->where('jawaban','=',$kategori->is_reverse)->orderBy('created_at','desc')->get();
                else
                    $monitoring_detail = MonitoringDetail::has('kategori')->where('kategori_id','=',$id)->whereHas('monitoring', function (Builder $query) use ($cabang, $t1, $t2) {
                        return $query->has('user')->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                    })->where('jawaban','=',$kategori->is_reverse)->orderBy('created_at','desc')->get();
            }
            elseif(Auth::user()->role->is_global == 0) {
                if(Auth::user()->role_id == role('admin-cabang')) {
                    $cabang = Auth::user()->attribute->cabang_id;
                    $monitoring_detail = MonitoringDetail::has('kategori')->where('kategori_id','=',$id)->whereHas('monitoring', function (Builder $query) use ($cabang, $t1, $t2) {
                        return $query->has('user')->where('cabang_id','=',$cabang)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                    })->where('jawaban','=',$kategori->is_reverse)->orderBy('created_at','desc')->get();
                }
                elseif(Auth::user()->role_id == role('admin-vendor')) {
                    $cabang = Auth::user()->attribute->cabang_id;
                    $vendor = Auth::user()->attribute->vendor_id;
                    $monitoring_detail = MonitoringDetail::has('kategori')->where('kategori_id','=',$id)->whereHas('monitoring', function (Builder $query) use ($vendor, $t1, $t2) {
                        return $query->has('user')->where('vendor_id','=',$vendor)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                    })->where('jawaban','=',$kategori->is_reverse)->orderBy('created_at','desc')->get();
                }
            }
    
            // Export to PDF
            if($request->query('print') == 'pdf') {
                $pdf = PDF::loadview('admin/monitoring/pdf-category', [
                    'kategori' => $kategori,
                    'monitoring_detail' => $monitoring_detail,
                    't1' => $t1,
                    't2' => $t2,
                ])->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);
                return $pdf->stream('Laporan Monitoring ATM.pdf');
            }
            // Export to Excel
            elseif($request->query('print') == 'excel') {
                // Get the cabang
                $cabang = Cabang::find($request->query('cabang'));

                // Filename
                $filename = $cabang ? 'Laporan Monitoring ATM di '.$cabang->nama.' ('.$kategori->awal.' '.($kategori->is_reverse == 0 ? 'Tidak' : '').' '.$kategori->akhir.')' : 'Laporan Monitoring ATM ('.$kategori->awal.' '.($kategori->is_reverse == 0 ? 'Tidak' : '').' '.$kategori->akhir.')';
                $filename = preg_replace("/[^a-zA-Z0-9_ -.,()]/s", '', $filename);

                return Excel::download(new MonitoringDetailExport($monitoring_detail), $filename.'.xlsx');
            }
            // View
            else {
                return view('admin/monitoring/category', [
                    'kategori' => $kategori,
                    'monitoring_detail' => $monitoring_detail,
                    'cabang' => $cabang,
                    't1' => $t1,
                    't2' => $t2,
                ]);
            }
        }
        else abort(403);
    }

    /**
     * Amount of user monitoring.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function amount(Request $request, $id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        if(Auth::user()->role_id == role('super-admin') || Auth::user()->role_id == role('admin-wilayah') || Auth::user()->role_id == role('admin-cabang') || Auth::user()->role_id == role('admin-vendor')) {
            // Get the user
            $user = User::findOrFail($id);

            // Get monitoring
            $monitoring = Monitoring::has('user')->has('cabang')->select(\DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') AS date_at"))->where('user_id','=',$user->id)->pluck('date_at')->toArray();
            $monitoring = array_count_values($monitoring);
            krsort($monitoring);
			
			// Get monitoring list
			$list = [];
			foreach($monitoring as $key=>$m) {
				$list[$key] = Monitoring::has('user')->has('cabang')->where('user_id','=',$user->id)->whereDate('created_at','=',$key)->get();
			}

            // Days
            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum`at', 'Sabtu'];

            // View
            return view('admin/monitoring/amount', [
                'user' => $user,
                'monitoring' => $monitoring,
                'list' => $list,
                'days' => $days,
            ]);
        }
        else abort(403);
    }
}
