<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\JobModel;
use App\Models\Jobsubmit;
use App\Models\Jobsignature;
use App\Models\Project;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Faker\Factory as Faker;

class AdminController extends Controller
{

    public function dashboard(Request $request){
        function hexToRgb($hex) {
            // ตรวจสอบว่ามีการใช้ # หรือไม่
            $hex = str_replace('#', '', $hex);

            // แปลง HEX เป็น RGB
            if (strlen($hex) == 6) {
                list($r, $g, $b) = str_split($hex, 2);
                $r = hexdec($r);
                $g = hexdec($g);
                $b = hexdec($b);
            } elseif (strlen($hex) == 3) {
                list($r, $g, $b) = str_split($hex, 1);
                $r = hexdec($r.$r);
                $g = hexdec($g.$g);
                $b = hexdec($b.$b);
            } else {
                return null; // สีไม่ถูกต้อง
            }

            return "rgb($r, $g, $b)";
        }

        $data = DB::table('tb_project')
        ->leftJoin('tb_job', 'tb_project.id', '=', 'tb_job.type')
        ->select(DB::raw("
            CASE
                WHEN tb_project.type IS NOT NULL THEN 'other'
                ELSE tb_project.name
            END as project_name"),
            DB::raw('count(tb_job.id) as total'),
            'tb_project.color'
        )
        ->groupBy('project_name', 'tb_project.color', 'tb_project.id')
        ->orderBy('tb_project.id', 'asc')  // เรียงตาม id ของ tb_project
        ->get();

$labels = json_encode($data->pluck('project_name'));

$colors = json_encode($data->map(function($item) {
    return hexToRgb($item->color);
}));
$dataarr = [];
foreach ($data as $item) {
    $dataarr[$item->project_name] = $item->total;
}
// ดึงค่า (values) ออกมาเป็น array
$values = array_values($dataarr);

// แปลงเป็น JSON
$valuesJson = json_encode($values);
// dd($labels,$valuesJson,$colors);
        // dd($labels,$colors);
        $min_id = DB::table('tb_project')
        ->leftJoin('tb_job', 'tb_project.id', '=', 'tb_job.type')
        ->select(DB::raw("
            CASE
                WHEN tb_project.type IS NOT NULL THEN 'other'
                ELSE tb_project.name
            END as project_name"),
            DB::raw('count(tb_job.id) as total'),
            'tb_project.id'
        )
        ->groupBy('project_name', 'tb_project.id')
        ->orderBy('tb_project.id', 'asc')
        ->pluck('tb_project.id')
        ->first();  // เอา id ที่น้อยที่สุด

        $status_in = JobModel::where('type',$min_id)->where('status_job','in progress')->get();
        $status_success = JobModel::where('type',$min_id)->where('status_job','success')->get();
        $status_wait = JobModel::where('type',$min_id)->where('status_job','wait')->get();
        $status_cancle = JobModel::where('type',$min_id)->where('status_job','cancle')->get();

        $dataarr_progress=[count($status_in), count($status_success), count($status_wait),count($status_cancle)];
        // dd($request->type);
        // if(!empty($request->show)){
            $report = JobModel::query()
            ->when($request->search, function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%');
            })
            ->when($request->type, function ($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->show ?? 10)
            ->appends([
                'type' => $request->type,
                'search' => $request->search,
                'show' => $request->show,
            ]);
            $colors = json_decode($colors); // หรือถ้าค่าของ $colors เป็น JSON string
            $labels = json_decode($labels); // เช่นเดียวกันสำหรับ labels
        $project=Project::where('type',null)->get();
        // dd($project);
        return view('backoffice.dashboard.index',[
            'dataarr'=>$dataarr,
            'dataarr_pie'=>$valuesJson,
            'dataarr_progress'=>$dataarr_progress,
            'report'=>$report,
            'show'=>$request->show,
            'project'=>$project,
            'colors'=>$colors,
            'labels'=>$labels,
            'search'=>$request->search,
            'type'=>$request->type,
        ]);
    }
    public function index(Request $request){
        $role = Auth::guard('admin')->user()->role;

        $admin=Admin::where('username', 'like', '%' . $request->search . '%')->orWhere('name', 'like', '%' . $request->search . '%')->paginate(10);
        return view('backoffice.admin.index',[
            'admin'=>$admin,
            'search'=>$request->search,
            'role'=>$role
        ]);
    }
    public function add(){
        return view('backoffice.admin.add');
    }
    public function addsub(Request $request){
        $validator=Validator::make($request->all(),[
            "name"=>'required|string',
            'username' => 'required|string|unique:admins,username',
            "password"=>'string',
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user=new Admin;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password =password_hash($request->password, PASSWORD_DEFAULT);
            $user->permission=$request->permission;
            $user->role = "admin";
            $user->save();
            return redirect("/admin")->with('success',1);
        }
    }
    public function edit($id){
        $admin=Admin::find($id);
        return view('backoffice.admin.edit',[
            'admin'=>$admin
        ]);
    }
    public function editsub(Request $request){
        // dd($request->all());
        $validator=validator::make($request->all(),[
            "name"=>"required|string",
            'username' => [
        'required',
        'string',
        Rule::unique('users', 'username')->ignore($request->id), // $userId คือ ID ของผู้ใช้ที่กำลังแก้ไข
    ],
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user=Admin::find($request->id);
            $user->name = $request->name;
            $user->username = $request->username;
            $user->permission=$request->permission;
            if($request->password != null and $request->password_confirmation != null){
                $user->password =password_hash($request->password, PASSWORD_DEFAULT);
            }else{

            }
            if($user->role != "superadmin"){
                $user->role = "admin";
            }
            $user->save();
            return redirect("/admin")->with('success',1);
        }
    }
    public function delete_admin(Request $request){
        $admin=Admin::destroy($request->id);
        return redirect('/admin')->with('success',1);
    }

    public function get_jobtype(Request $request){

    // นับจำนวน status_job โดยกรองตาม type_id ที่รับมา
    $projectId = $request->data;

    // กำหนดค่าเริ่มต้นเป็น 0 สำหรับสถานะที่ไม่มีในข้อมูล
        $status_in = JobModel::where('type',$projectId)->where('status_job','in progress')->get();
        $status_success = JobModel::where('type',$projectId)->where('status_job','success')->get();
        $status_wait = JobModel::where('type',$projectId)->where('status_job','wait')->get();
        $status_cancle = JobModel::where('type',$projectId)->where('status_job','cancle')->get();

    // สร้าง array ที่เก็บจำนวน
    $dataarr_progress=[count($status_in), count($status_success), count($status_wait),count($status_cancle)];

    $dataarr_progress=json_encode($dataarr_progress);
        return response()->json([
            'dataarr_progress' => $dataarr_progress ,// ส่งค่า dataarr_progress กลับไป
            // 'dataarr_progress_count' => $dataarr_progress_count
        ]);
    }

    public function view_submit($id){
        $job = JobModel::find($id);
        $file_job = Jobsubmit::where('job_id',$id)->get();
        $sugnature = Jobsubmit::where('job_id',$id)->first();
        if($job->type == "Tesla"){
            return view('backoffice.job.view.tesla',[
                'job'=>$job,
                'file_job'=>$file_job,
                'sugnature'=>$sugnature,
            ]);
        }
        if($job->type == "Service"){
            return view('backoffice.job.view.service',[
                'job'=>$job,
                'file_job'=>$file_job,
                'sugnature'=>$sugnature,
            ]);
        }
        if($job->type == "Prime"){
            return view('backoffice.job.view.prime',[
                'job'=>$job,
                'file_job'=>$file_job,
                'sugnature'=>$sugnature,
            ]);
        }
        if($job->type == "วงจร"){
            return view('backoffice.job.view.cycle',[
                'job'=>$job,
                'file_job'=>$file_job,
                'sugnature'=>$sugnature,
            ]);
        }
        if($job->type == "CCTV"){
            return view('backoffice.job.view.cctv',[
                'job'=>$job,
                'file_job'=>$file_job,
                'sugnature'=>$sugnature,
            ]);
        }
        if($job->type == "other"){
            return view('backoffice.job.view.other',[
                'job'=>$job,
                'file_job'=>$file_job,
                'sugnature'=>$sugnature,
            ]);
        }
    }
    public function search(Request $request){
        $query = $request->input('query');

        // ค้นหาจากฐานข้อมูล
        $results = JobModel::where('customer_name', 'LIKE', "%{$query}%")->get();

        return response()->json($results);
    }

    public function privacy(){
        return view('backoffice.privacy');
    }

    public function dashboard_search(Request $request){
        function hexToRgb($hex) {
            // ตรวจสอบว่ามีการใช้ # หรือไม่
            $hex = str_replace('#', '', $hex);

            // แปลง HEX เป็น RGB
            if (strlen($hex) == 6) {
                list($r, $g, $b) = str_split($hex, 2);
                $r = hexdec($r);
                $g = hexdec($g);
                $b = hexdec($b);
            } elseif (strlen($hex) == 3) {
                list($r, $g, $b) = str_split($hex, 1);
                $r = hexdec($r.$r);
                $g = hexdec($g.$g);
                $b = hexdec($b.$b);
            } else {
                return null; // สีไม่ถูกต้อง
            }

            return "rgb($r, $g, $b)";
        }

        $data = DB::table('tb_project')
        ->leftJoin('tb_job', 'tb_project.id', '=', 'tb_job.type')
        ->select(DB::raw("
            CASE
                WHEN tb_project.type IS NOT NULL THEN 'other'
                ELSE tb_project.name
            END as project_name"),
            DB::raw('count(tb_job.id) as total'),
            'tb_project.color'
        )
        ->groupBy('project_name', 'tb_project.color', 'tb_project.id')
        ->orderBy('tb_project.id', 'asc')  // เรียงตาม id ของ tb_project
        ->get();

$labels = json_encode($data->pluck('project_name'));

$colors = json_encode($data->map(function($item) {
    return hexToRgb($item->color);
}));
$dataarr = [];
foreach ($data as $item) {
    $dataarr[$item->project_name] = $item->total;
}
// ดึงค่า (values) ออกมาเป็น array
$values = array_values($dataarr);

// แปลงเป็น JSON
$valuesJson = json_encode($values);
// dd($labels,$valuesJson,$colors);
        // dd($labels,$colors);
        $min_id = DB::table('tb_project')
        ->leftJoin('tb_job', 'tb_project.id', '=', 'tb_job.type')
        ->select(DB::raw("
            CASE
                WHEN tb_project.type IS NOT NULL THEN 'other'
                ELSE tb_project.name
            END as project_name"),
            DB::raw('count(tb_job.id) as total'),
            'tb_project.id'
        )
        ->groupBy('project_name', 'tb_project.id')
        ->orderBy('tb_project.id', 'asc')
        ->pluck('tb_project.id')
        ->first();  // เอา id ที่น้อยที่สุด

        $status_in = JobModel::where('type',$min_id)->where('status_job','in progress')->get();
        $status_success = JobModel::where('type',$min_id)->where('status_job','success')->get();
        $status_wait = JobModel::where('type',$min_id)->where('status_job','wait')->get();
        $status_cancle = JobModel::where('type',$min_id)->where('status_job','cancle')->get();

        $dataarr_progress=[count($status_in), count($status_success), count($status_wait),count($status_cancle)];
        // dd($dataarr_progress);
        $search = $request->input('search'); // รับค่าจาก input ที่ชื่อ search
        $report=JobModel::where('job_name', 'like', '%' . $search . '%')->orwhere('customer_name','like','%'.$search.'%')->orderBy('created_at', 'desc')
            ->paginate($request->show ?? 10);

        if($search == null){
            return redirect('/');
        }
        $project=Project::whereNull('type')->get();
        return view('backoffice.dashboard.index',[
            'dataarr'=>$dataarr,
            'dataarr_pie'=>$valuesJson,
            'dataarr_progress'=>$dataarr_progress,
            'report'=>$report,
            'show'=>$request->show,
            'project'=>$project,
            'colors'=>$colors,
            'labels'=>$labels,
            'searched' => $search ? true : false, // ส่งค่าบอกว่ามีการ search
            'search' => $search, // ส่งค่าที่ค้นหากลับไปที่ view
        ]);
    }
}
