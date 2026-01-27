<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\JobModel;
use Illuminate\Http\Request;
use App\Models\Jobsignature;
use App\Models\Jobsubmit;
use App\Models\JobFile;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Project_submit;
use DateTime;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\reset_password;
class JobsubmitController extends Controller
{
    public function submit_file(Request $request){
        // $user = Auth::user();
        // $userlist = User::all();
        $job=JobModel::where('id',$request->job_id)->first();
        // dd('a');

        // dd($request->file('image'));
        // return response()->json(['file'=>$request->file()]);
        if($request->file('file') != null){

            $files = $request->file('file');

            $uploadedFiles = [];
            foreach ($files as $file) {
                $fileName = date('dmY').'_'.$file->getClientOriginalName();
                // dd($fileName);
                $file->move(public_path('assets/images/submit/job'), $fileName);
                $uploadedFiles[] = $fileName;
                Jobsubmit::create([
                    'job_id'=>$request->job_id,
                    'step_file'=>$request->step,
                    'file_name'=>$fileName,
                    'path'=>'assets/images/submit/job',
                    'detail'=>$request->detail,
                    'status'=>$job->status
                ]);


            }
            // dd($job);
            // $job->status_job='success';
            // $job->save();
            // $check = Project_submit::where('id',$request->step)->get();
        return response()->json([
            'success' => 'อัพโหลดรูปสำเร็จ',
            'path'=>'assets/images/submit/job',
            'files'=>$uploadedFiles,
            'step'=>$request->step,
            'job_id'=>$request->job_id,
            // 'status'=>$job->status_job
        ], 200);
        }else{
            return response()->json([
                'message'=>'กรุณาแนบไฟล์'
            ],200);
        }


    }
    public function check_in(Request $request){
        $user = Auth::user();
        $job = JobModel::where('id',$request->job_id)->first();
        // dd($job);
        $job->status_job = 'in progress';
        $job->start_progress = Carbon::now();
        $job->save();
        return response()->json([
            'message'=>'success',
            'status'=>$job->status_job,
            'check'=>$user->role
        ],200);
    }
    public function submit_signature(Request $request){

        // $check=Project_submit
        // dd($request->file('file')->getError());
        $job = JobModel::find($request->job_id);
        $project_submit = Project_submit::where('project_id', $job->type)
        ->where(function ($query) use ($job) {
            $query->where('job_id', $job->id)
                  ->orWhereNull('job_id');
        })
        ->get();

        $count_project_submit = count($project_submit);

        $uniqueStepFileCount = Jobsubmit::where('job_id', $job->id)
        ->distinct('step_file') // Ensure that the step_file is unique
        ->count('step_file');
        // dd($project_submit,$uniqueStepFileCount);
        if($count_project_submit == $uniqueStepFileCount){

        // dd($uniqueStepFileCount);
        // dd($count_project_submit);
        $files = $request->file('file');
        // dd($_FILES['file']);
        $type=JobModel::find($request->job_id);
            $fileName = date('dmY_His').'_'.$files->getClientOriginalName();
            // dd($filesName);
            $files->move(public_path('assets/images/submit/signature'), $fileName);
            $uploadedFiles[] = $fileName;
            Jobsignature::create([
                'job_id'=>$request->job_id,
                'signature'=>$fileName,
                'type'=>$type->status,
                'path'=>'assets/images/submit/signature',
            ]);
            $job = JobModel::find($request->job_id);
            $job->status_job = 'success';
            $job->end_progress = Carbon::now();
            $job->save();


    return response()->json([
        'success' => 'ส่งงานเรียบร้อย',
        'files'=>$uploadedFiles,
        'step'=>$request->step,
        'job_id'=>$request->job_id,
        'status_job'=>'success'
    ], 200);
}else{
    return response()->json([
        'status'=>false,
        'success' => 'กรุณาส่งงานให้ครบทุกหัวข้อ',
        'status_job'=>'false'
    ], 400);
}

    }
    public function get_job_all(){
        Carbon::setLocale('th');
        $date_now = Carbon::now();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();        // $ymd = "2024-04-11T00:";
        // $ymd = $date_now->thaidate('Y-m-d');
        $month = $date_now->format('m');
        $year = $date_now->format('Y');
        $date = $date_now->format('d');

        // $token = User::
        $user = Auth::user();
        // dd($user);
        if($user->role == "technician"){
            $job = JobModel::whereBetween('install_date', [$startOfMonth, $endOfMonth])
            ->whereBetween('survay_date', [$startOfMonth, $endOfMonth])->where('status','!=','สำรวจ')
            ->where('technician',$user->team_id)->get();
        }else{
            $job = JobModel::whereBetween('install_date', [$startOfMonth, $endOfMonth])
            ->whereBetween('survay_date', [$startOfMonth, $endOfMonth])
            ->where('engineer',$user->id)->get();
        }
        // if(empty($job)){
        //     $job = "ไม่พบรายการ";
        // }
        return response()->json([
            'date_now'=>$date_now,
            'year'=>$year,
            'month'=>$month,
            'date'=>$date,
            'user'=>$user,
            'job'=>$job
        ]);
    }
    public function get_job_filter(Request $request){
        Carbon::setLocale('th');
        $date_now = Carbon::now();
        // dd($request->all());
        // if(!empty($request->year)){

        if ($request->date != null) {
            // $a=1;
            $date = Carbon::parse($request->date);
            $year = $date->year;
            $month = $date->month;
            $day = $date->day;
            $startOfMonth = Carbon::create($year, $month, $day)->toDateString();
            $endOfMonth = Carbon::create($year, $month, $day)->toDateString();
        } else {
            // $a=2;
            $year = $request->year;
            $month = $request->month;
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        }
        $user = Auth::user();

        $search = $request->search ?? '';

        $jobs = JobModel::where(function($query) use ($user) {
                    $query->where('technician', $user->team_id)
                    ->orWhereRaw("FIND_IN_SET(?, REPLACE(engineer, ' ', ''))", [$user->id]) // เช็ค engineer ด้วย FIND_IN_SET
                    ->orWhere('sale', $user->id);
                })
                ->when($user->role == 'technician', function($query) {
                    $query->where('status', '!=', 'สำรวจ'); // เงื่อนไขสำหรับ technician
                })
                ->when($search, function($query) use ($search) {
                    $query->where('job_name', 'like', '%' . $search . '%')
                          ->orWhere('customer_name', 'like', '%' . $search . '%');
                })
                ->where(function($query) use ($startOfMonth, $endOfMonth, $user, $request) {
                    if ($request->date != null) {
                        // ถ้ามีค่า $request->date
                        $date = $request->date; // ค่าวันที่ที่ต้องการค้นหา
                        $query->whereDate('install_date', $date)
                              ->orWhereDate('survay_date', $date)
                              ->orWhereDate('service_date', $date);
                    } else {
                        // ถ้าไม่มีค่า $request->date
                        $query->whereBetween('install_date', [$startOfMonth, $endOfMonth])
                              ->orWhereBetween('survay_date', [$startOfMonth, $endOfMonth])
                              ->orWhereBetween('service_date', [$startOfMonth, $endOfMonth]);
                    }
                })
                ->when($request->status || $request->status_job, function($query) use ($request) {
                    $query->where(function($query) use ($request) {
                        if ($request->status) {
                            $query->where('status', $request->status);
                        }
                        if ($request->status_job) {
                            $query->orWhere('status_job', $request->status_job);
                        }
                    });
                })
                ->get()
                ->map(function ($job) {
                    // แปลง format วันที่
                    $job->install_date = $job->install_date ? Carbon::parse($job->install_date)->format('Y-m-d H:i') : null;
                    $job->survay_date = $job->survay_date ? Carbon::parse($job->survay_date)->format('Y-m-d H:i') : null;
                    $job->service_date = $job->service_date ? Carbon::parse($job->service_date)->format('Y-m-d H:i') : null;
                    return $job;
                });



// Step 2: Extract the type_id values from the jobs
$typeIds = $jobs->pluck('type')->unique();

// Step 3: Query the tb_project using the extracted type_id values
$projects = Project::whereIn('id', $typeIds)->get();


        // if(empty($job)){
        //     $job = "ไม่พบรายการ";
        // }
        return response()->json([
            // 'a'=>$a,
            'date'=>$request->date,
            'startOfMonth'=>$startOfMonth,
            'endOfMonth'=>$endOfMonth,
            'year'=>$year,
            'month'=>$month,
            'user'=>$user->id,
            'job'=>$jobs,
            'project_name'=>$projects,
            'search'=>$search,

        ]);
    }
    public function get_job_filter_test(Request $request){
        Carbon::setLocale('th');
        $date_now = Carbon::now();
        // dd($request->all());
        // if(!empty($request->year)){

        if ($request->date != null) {
            // $a=1;
            $date = Carbon::parse($request->date);
            $year = $date->year;
            $month = $date->month;
            $day = $date->day;
            $startOfMonth = Carbon::create($year, $month, $day)->toDateString();
            $endOfMonth = Carbon::create($year, $month, $day)->toDateString();
        } else {
            // $a=2;
            $year = $request->year;
            $month = $request->month;
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        }
        
      
        $user = Auth::user();

        $search = $request->search ?? '';

        $jobs = JobModel::where(function($query) use ($user) {
            if ($user->role === 'technician') {
                $query->where('technician', $user->team_id);
            } elseif ($user->role === 'engineer') {
                $query->whereRaw("FIND_IN_SET(?, REPLACE(engineer, ' ', ''))", [$user->id]);
            } elseif ($user->role === 'sale') {
                $query->where('sale', $user->id);
            }
        })
        ->when($user->role === 'technician', function($query) {
            $query->where('status', 'ติดตั้ง');
        })
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('job_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
        })
        ->where(function($query)  use ($startOfMonth, $endOfMonth) {
            $query->whereDate('install_date', '>=', $startOfMonth)
          ->whereDate('install_date', '<=', $endOfMonth)
          ->orWhereDate('survay_date', '>=', $startOfMonth)
          ->whereDate('survay_date', '<=', $endOfMonth)
          ->orWhereDate('service_date', '>=', $startOfMonth)
          ->whereDate('service_date', '<=', $endOfMonth);
        })
        ->when($request->status || $request->status_job, function($query) use ($request) {
            $query->where(function($q) use ($request) {
                if ($request->status) {
                    $q->where('status', $request->status);
                }
                if ($request->status_job) {
                    $q->orWhere('status_job', $request->status_job);
                }
            });
        })
        ->get();
       

    
                // Step 2: Extract the type_id values from the jobs
                $typeIds = $jobs->pluck('type')->unique();

                // Step 3: Query the tb_project using the extracted type_id values
                $projects = Project::whereIn('id', $typeIds)->get()->keyBy('id'); // ทำ keyBy id เลย

                $jobs = $jobs->map(function ($job) use ($projects) {
                    $job->install_date = $job->install_date ? Carbon::parse($job->install_date)->format('Y-m-d') : null;
                    $job->survay_date = $job->survay_date ? Carbon::parse($job->survay_date)->format('Y-m-d') : null;
                    $job->service_date = $job->service_date ? Carbon::parse($job->service_date)->format('Y-m-d') : null;
            
                    // เลือกวันที่ที่จะใช้เป็น key (เช่น install_date เป็นหลัก)
                    $job->display_date = $job->install_date ?? $job->survay_date ?? $job->service_date;
            
                    // เพิ่ม project_name เข้าไปในแต่ละ job
                    $job->project_name = $projects[$job->type]->name ?? 'ไม่พบโปรเจค';
            
                    return $job;
                })
                ->sortByDesc('display_date') // <<== เพิ่มบรรทัดนี้ เพื่อเรียงจากวันที่ใหม่ไปเก่า
                ->groupBy('display_date');   
                
        return response()->json([
            // 'a'=>$a,
            'date'=>$request->date,
            'startOfMonth'=>$startOfMonth,
            'endOfMonth'=>$endOfMonth,
            'year'=>$year,
            'month'=>$month,
            'user'=>$user->id,
            'job'=>$jobs,
            'project_name'=>$projects,
            'search'=>$search,

        ]);
    }
    public function get_job(Request $request){
        $user = Auth::user();
        $job = JobModel::where('id',$request->job_id)->first();
        $file = JobFile::where('job_id',$job->id)->get();
        $project = Project::where('id',$job->type)->first();

        $results = Jobsubmit::join('tb_submit_project', 'tb_submit_job.step_file', '=', 'tb_submit_project.id')
    ->where('tb_submit_job.job_id', $job->id)
    ->select(
        'tb_submit_job.id',
        'tb_submit_job.step_file',
        'tb_submit_job.path',
        'tb_submit_job.file_name',
        'tb_submit_project.name',
        DB::raw("COALESCE(tb_submit_job.detail, '-') as detail") // เปลี่ยน NULL เป็น '-'
    )
    ->get();

        return response()->json([
            'user'=>$user,
            'job'=>$job,
            'file'=>$file,
            'project_name'=>$project->name,
            'submit_form_technician'=>$results
        ]);
    }
    public function job_submit_in_progress(Request $request){
        // $user = Auth::user();
        $job = JobModel::find($request->job_id);
        $job->status_job = 'in progress';
        $job->save();
        // $file = JobFile::where('job_id',$job->id)->get();
        $job = JobModel::where('id',$request->job_id)->select('status_job')->first();
        return response()->json([
            'status_job'=>$job,
        ]);
    }

    public function back_step_job(Request $request){
        $user = Auth::user();
        // $job = JobModel::find($request->job_id);
        // $file = JobFile::where('job_id',$job->id)->where('step_file',$request->step)->get();
        return response()->json([
            'user'=>$user,
            'job'=>$request->job_id,
            // 'file'=>$file,
            'step'=>$request->step
        ]);
    }
    public function submit_project(Request $request){
        // return response()->json([
        //     'job_id'=>$request->job_id,
        //     'type'=>$request->type
        // ]);
        $project_name=Project::where('id',$request->type)->first();
        $project_submit = Project_submit::where('project_id', $request->type)
    ->where(function($query) use ($request) {
        $query->where('job_id', $request->job_id)
              ->orWhereNull('job_id');
    })
    ->get();
    if(!$project_submit){
        $project_submit="ไม่พบหัวข้อส่งงาน";
    }
    // dd($project_name);
        return response()->json([
            'project_name'=>$project_name->name,
            'submit_project'=>$project_submit,
        ]);
    }
    public function check_list(Request $request){
        $check = Jobsubmit::where('job_id', $request->job_id)
        ->groupBy('step_file')
        ->pluck('step_file')
        ->toArray();
        return response()->json(['step'=>$check]);
    }

    public function image_get(Request $request){
        $image = Jobsubmit::where('job_id',$request->job_id)->where('step_file',$request->step)->get();
        return response()->json(['image'=>$image]);
    }
    public function delete_image(Request $request){
        $img = DB::table('tb_submit_job')->where('id',$request->id)->first();
        $direction = public_path("/assets/images/submit/job/").$img->file_name;
        if(is_file($direction)){
            unlink($direction);// ลบไฟล์รูป
        }
        // return response()->json(['data'=>$img]);
        $a=DB::table('tb_submit_job')->where('id',$request->id)->delete();
        if($a){
            return response()->json(["result"=>"success"]);
        }else{
            return response()->json(["result"=>"error"]);
        }
        // return redirect("/job/edit/$request->job_id")->with('success',1);
    }
    public function year(){
        $dates = DB::table('tb_job')
        ->select('install_date', 'survay_date', 'service_date')
        ->get();

    $years = [];

    // Function to check if time is :00
    $isValidTime = function($datetime) {
        if (!$datetime) return false;
        // Extract time part
        $time = date('H:i', strtotime($datetime));
        return $time !== '00:00';
    };

    foreach ($dates as $date) {
        // Check install_date
        if ($isValidTime($date->install_date)) {
            $years[] = date('Y', strtotime($date->install_date));
        }
        // Check survay_date
        if ($isValidTime($date->survay_date)) {
            $years[] = date('Y', strtotime($date->survay_date));
        }
        // Check service_date
        if ($isValidTime($date->service_date)) {
            $years[] = date('Y', strtotime($date->service_date));
        }
    }

    // Remove duplicate years and sort them in ascending order
    $uniqueYears = array_unique($years);
    sort($uniqueYears);  // Sorts the array in ascending order (oldest to newest)

    // Example output
    return response()->json(array_values($uniqueYears));
        }
        public function calendar(Request $request) {
            $user = Auth::user();
            $status = $request->status; // รับค่า status จาก request

            // เริ่มต้นการสร้าง query
            $query = JobModel::query();

            // กรองข้อมูลตามสถานะและบทบาทของผู้ใช้
            if ($status) {
                switch ($user->role) {
                    case 'technician':
                        if ($status == 'ติดตั้ง') {
                            $query->where('technician', $user->team_id)
                                  ->where('status', $status)
                                  ->whereNotNull('install_date');
                        }
                        break;

                    case 'engineer':
                        if ($status == 'ติดตั้ง') {
                            $query->whereRaw("FIND_IN_SET(?, REPLACE(engineer, ' ', ''))", [$user->id])
                                  ->where('status', $status)
                                  ->whereNotNull('install_date');
                        } elseif ($status == 'สำรวจ') {
                            $query->whereRaw("FIND_IN_SET(?, REPLACE(engineer, ' ', ''))", [$user->id])
                                  ->where('status', $status)
                                  ->whereNotNull('survay_date');
                        }
                        break;

                    case 'sale':
                        if ($status == 'ติดตั้ง') {
                            $query->whereRaw("FIND_IN_SET(?, REPLACE(sale, ' ', ''))", [$user->id])
                                ->where('status', $status)
                                ->whereNotNull('install_date');
                        } elseif ($status == 'สำรวจ') {
                            $query->whereRaw("FIND_IN_SET(?, REPLACE(sale, ' ', ''))", [$user->id])
                                ->where('status', $status)
                                ->whereNotNull('survay_date');
                        } elseif ($status == 'แก้ไข') {
                            $query->whereRaw("FIND_IN_SET(?, REPLACE(sale, ' ', ''))", [$user->id])
                                ->where('status', $status)
                                ->whereNotNull('service_date');
                        }
                        break;

                }
            } else {
                // หากไม่มีสถานะ ให้กรองตามบทบาทของผู้ใช้
                switch ($user->role) {
                    case 'technician':
                        $query->where('technician', $user->team_id)
                              ->whereNotNull('install_date');
                        break;

                    case 'engineer':
                        $query->whereRaw("FIND_IN_SET(?, REPLACE(engineer, ' ', ''))", [$user->id])
                              ->where(function($query) {
                                  $query->whereNotNull('install_date')
                                        ->orWhereNotNull('survay_date');
                              });
                        break;

                    case 'sale':
                        $query->where('sale', $user->id)
                              ->where(function($query) {
                                  $query->whereNotNull('install_date')
                                        ->orWhereNotNull('survay_date')
                                        ->orWhereNotNull('service_date');
                              });
                        break;
                }
            }

            // สร้าง select เพื่อคำนวณวันที่และจำนวนงาน
           $jobs = $query->selectRaw("
                CASE
                    WHEN status = 'ติดตั้ง' THEN DATE(install_date)
                    WHEN status = 'สำรวจ' THEN DATE(survay_date)
                    WHEN status = 'แก้ไข' THEN DATE(service_date)
                END as date,
                status,
                COUNT(DISTINCT id) as job_count
            ")
            ->groupBy('date', 'status')
            ->get();

            // แปลงข้อมูลเป็น response
            $response = $jobs->groupBy('date')->map(function($jobs, $date) {
                return [
                    'date' => $date,
                    'total_jobs' => $jobs->sum('job_count')
                ];
            })->values();

            return response()->json($response);
        }
        

    public function reset_password(Request $request){
        $username = $request->username;
        $name = $request->name;
        $check = User::where('name',$name)->where('username',$username)->first();
        if(empty($check)){
            return response()->json([
                'message'=>'ไม่พบข้อมูลผู้ใช้'
            ],401);
        }
        if($check->role == 'technician'){
            $link = '/technician/edit/'.$check->id;
        }elseif($check->role == 'engineer'){
            $link = '/engineer/edit/'.$check->id;
        }else{
            $link = '/sale/edit/'.$check->id;
        }
        $url = url($link);
        // return response()->json(['link'=>$url]);

        $data=[
            'subject'=>'คำร้องขอตั้งค่ารหัสผ่านใหม่',
            'name'=>$name,
            'username'=>$username,
            'url'=>$url
        ];
        Mail::send(new reset_password($data));
        return response()->json([
            // 'check'=>$check,
            'message'=>'ส่งคำร้องขอเรียบร้อย กรุณารอ admin ติดต่อกลับ'
        ]);
    }
    public function image_app(Request $request){
        try {
            $file_details = [];
            $files = $request->file('file'); // Assuming 'files' is the name of the input field
            $user = Auth::user();
            foreach ($files as $value) {
                $filename         = $value->getClientOriginalName();
                $extension        = $value->getClientOriginalExtension();
                $size             = $value->getSize();
                $file_detail      = date('His') . $filename;
                $destinationPath  = public_path("/assets/images/job_file/");
                $value->move($destinationPath, $file_detail);

                $val = array(
                    'job_id'       => $request->job_id,
                    'file_name'    => $file_detail,
                    'path'         => "/assets/images/job_file/",
                    'type'         => $extension,
                    'role_user'    => $user->role,
                    'created_at'   => new DateTime,
                    'updated_at'   => new DateTime
                );
                $file_details[] = $val;
            }

// Insert all file data at once
                DB::table('tb_file_job')->insert($file_details);
                $data = JobFile::where('job_id',$request->job_id)->get();
                if($user->role == 'engineer'){
                    $job=JobModel::where('id',$request->job_id)->first();
                    $job->status_job='success';
                    $job->save();
                }

            return response()->json([
                'message' => 'success',
                'data'=>$data

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
            }
    }
