<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobModel;
use App\Models\JobFile;
use DateTime;
use DB;
use App\Models\Project_submit;
use App\Models\Project;
class JobController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search', old('search'));
    $project = $request->input('project', old('project'));
    $status = $request->input('status', old('status'));
    $sale = $request->input('sale', old('sale'));
    $engineer = $request->input('engineer', old('engineer'));
    $team = $request->input('team', old('team'));
    $show = $request->input('show', $request->show ?? 10);

    $job = JobModel::query();

    // เพิ่มเงื่อนไขตามค่าที่ได้รับ
    if (!empty($search)) {
        $job->where('job_name', 'like', '%' . $search . '%');
        $job->orWhere('customer_name', 'like', '%' . $search . '%');
    }

    if (!empty($project) && $project !== 'All') {
        $job->where('type', $project);
    }

    if (!empty($status) && $status !== 'All') {
        $job->where('status', $status);
    }

    if (!empty($sale) && $sale !== 'All') {
        $job->where('sale', $sale);
    }

    if (!empty($engineer) && $engineer !== 'All') {
        $engineerPattern = '%,' . $engineer . ',%';
        $engineerPatternStarts = $engineer . ',%';
        $engineerPatternEnds = '%,' . $engineer;

        $job->where(function($query) use ($engineer, $engineerPattern, $engineerPatternStarts, $engineerPatternEnds) {
            $query->where('engineer', 'like', $engineerPattern)
                  ->orWhere('engineer', 'like', $engineerPatternStarts)
                  ->orWhere('engineer', 'like', $engineerPatternEnds)
                  ->orWhere('engineer', $engineer);
        });
    }

    if (!empty($team) && $team !== 'All') {
        $job->where('technician', $team);
    }

    // เรียงลำดับและแบ่งหน้าข้อมูล
    $job = $job->orderBy('created_at', 'desc')->paginate($show);

    return view('backoffice.job.index', [
        'job' => $job,
        'search' => $search,
        'projects' => $project,
        'status' => $status,
        'sales' => $sale,
        'engineers' => $engineer,
        'teams' => $team,
        'show' => $show
    ]);
}

    public function add(){
        $project=Project::whereNull('type')->get();
        $province=DB::table('thai_provinces')->get();
        // $district=DB::table('thai_amphures')->get();
        return view('backoffice.job.add',[
            'project'=>$project,
            'province'=>$province,
            // 'district'=>$district,

        ]);
    }
    public function addsub(Request $request){
        // dd($request->all());
        $check = Project_submit::where('project_id',$request->type_a)->where('project_id','!=',39)->count();
        // dd($request->input('inputs'));
        if($check == 0 and $request->input('inputs') == null){
            return redirect()->back()->with('error','ไม่พบหัวข้อส่งงานของงานที่เลือก กรุณาเพิ่มหัวข้อส่งงานของ Project ก่อน');
        }
        $result = '';
        $sale = '';
        if($request->engineer != null){
        $result = implode(", ", $request->engineer);
        }
        if($request->sale != null){
        $sale = implode(", ", $request->sale);
        }
        $job=new JobModel;
        $job->job_name=$request->job_name;
        $job->phase=$request->phase;
        if($request->type == 'other'){
            $job->other_detail=$request->other_detail;
            $project = new Project;
            $project->name=$request->other_detail;
            $project->save();
            $job->type=$project->id;

        }else{
        $job->type=$request->type_a;

        }
        if($request->phase == 'other'){
            $job->other_phase=$request->other_detail_phase;
        }
        $job->survay_date= $request->survay_date;
        $job->install_date= $request->Install_date;
        $job->service_date= $request->Service_date;
        $job->sale=$sale;
        $job->address_customer=$request->address;
        $job->status=$request->status;
        $job->customer_name=$request->customer;
        $job->tell=$request->tell;
        // $job->google_latitude=$request->google_latitude;
        // $job->google_longitude=$request->google_longitude;
        $job->technician=$request->technician;
        $job->engineer=$result;
        $job->job_detail=$request->job_detail;
        $job->other_detail=$request->other_detail;
        $job->province=$request->provinces;
        $job->district=$request->districts;
        $job->google_map=$request->google_map;
        $job->status_job = 'wait';
        $job->save();



        $job_file=JobModel::latest()->first();
        $inputs = $request->input('inputs');

        // ตรวจสอบว่าได้รับข้อมูล inputs หรือไม่
        if (is_array($inputs)) {
            foreach ($inputs as $input) {
                // คุณสามารถบันทึกข้อมูลลงฐานข้อมูล หรือประมวลผลตามที่คุณต้องการได้ที่นี่
                // ตัวอย่างการบันทึกข้อมูลลงในฐานข้อมูล
                $submit=new Project_submit;
                $submit->project_id=$request->type_a;
                $submit->job_id=$job_file->id;
                $submit->name=$input;
                $submit->save();
            }
        }
        $file_detail = [];
if ($request->file('file_detail')) {
    foreach ($request->file('file_detail') as $key => $value) {
        $filename = $value->getClientOriginalName();
        $extension = $value->getClientOriginalExtension();
        $size = $value->getSize();

        // สร้างชื่อไฟล์ใหม่เพื่อป้องกันการชนกัน
        $newFileName = $key . date('His') . '.' . $extension;
        $destinationPath = public_path("/assets/images/job_file/");
        $value->move($destinationPath, $newFileName);

        $file_detail[] = [
            'job_id' => $job_file->id,
            'file_name' => $newFileName,
            'path' => "/assets/images/job_file/",
            'type' => $extension,
            'role_user' => 'admin',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ];
    }

    // เพิ่มข้อมูลลงฐานข้อมูลในคราวเดียว
    DB::table('tb_file_job')->insert($file_detail);
}

        return redirect('/job')->with('success',1);
    }
    public function edit($id){

        $job = JobModel::find($id);

        // ดึง job ทั้งหมดที่มีชื่อเดียวกัน
        $jobsWithSameName = JobModel::where('job_name', $job->job_name)->pluck('id');

        // ดึงไฟล์รูปภาพจากทุก job_id ที่มีชื่อเดียวกัน
        $job_img = JobFile::whereIn('job_id', $jobsWithSameName)
            ->whereIn('type', ["jpg", "png", "jpeg", "webp", "jfif"])
            ->get();

        // ดึงไฟล์ที่ไม่ใช่รูปภาพ จาก job เดิมเท่านั้น
        $job_file = JobFile::where('job_id', $id)
            ->whereNotIn('type', ["jpg", "png", "jpeg", "webp", "jfif"])
            ->get();
        $project = Project::whereNull('type')->get();
        $check = Project::where('id', $job->type)->first();

        if ($check && $check->type == 1) { // Check if $check is not null and then access the 'type' property
            $submit = Project_submit::where('project_id', $check->id)->where('job_id', $id)->get();
        } else {
            $submit = Project_submit::where('project_id', $job->type)->where('job_id', $id)->get();
        }

        $province = DB::table('thai_provinces')->get();
        $district = DB::table('thai_amphures')->get();

        return view('backoffice.job.edit', [
            'job' => $job,
            'job_file' => $job_file,
            'job_img' => $job_img,
            'project' => $project,
            'submit' => $submit,
            'province' => $province,
            'district' => $district,
        ]);
    }

    public function editsub(Request $request){
        // dd($request->status_job);
        // dd($request->all());
        // $check = Project_submit::where('project_id',$request->type_a)->where('project_id','!=',39)->count();
        // if($check == 0){
        //     return redirect()->back()->with('error','ไม่พบหัวข้อส่งงานของงานที่เลือก กรุณาเลือกหัวข้อส่งงานก่อน');
        // }
        $job=JobModel::find($request->id);
        $job->job_name=$request->job_name;
        $job->phase=$request->phase;
        // dd($request->type_a);
        if($request->type_a == 'other'){
            $job->other_detail=$request->other_detail;
            $check=Project::where('name',$request->other_detail)->first();
            // dd($check);
            if(!empty($check)){
                $check->name=$request->other_detail;
                $check->save();
            }else{
                $project = new Project;
                $project->name=$request->other_detail;
                $project->type='1';
                $project->color='#000';
                $project->save();
                $job->type=$project->id;

            }
        }else{
        $job->type=$request->type_a;

        }
        if($request->phase == 'other'){
            $job->other_phase=$request->other_detail_phase;
        }


        if($request->survay_date != null){
            $job->survay_date = $request->survay_date;
        }else{
            $job->survay_date = null;
        }


        if($request->install_date != null){
            $job->install_date = $request->install_date;
        }else{
            $job->install_date = null;
        }



        if($request->service_date != null){
            $job->service_date = $request->service_date;
        }else{
            $job->service_date = null;
        }
        $result = '';
        $sale = '';
        if($request->engineer != null){
        $result = implode(", ", $request->engineer);
        }
        if($request->sale != null){
        $sale = implode(", ", $request->sale);
        }
        $job->sale=$sale;
        $job->address_customer=$request->address;
        if($request->status_job != null){
            $job->status_job= "cancle";
        }else{
            // dd($job->status != $request->status);

            $job->status_job= "wait";

        }
        $job->status=$request->status;
        // dd($job->status);
        $job->customer_name=$request->customer;
        $job->tell=$request->tell;
        // $job->google_latitude=$request->google_latitude;
        // $job->google_longitude=$request->google_longitude;
        $job->technician=$request->technician;
        $job->engineer=$result;
        $job->job_detail=$request->job_detail;
        $job->other_detail=$request->other_detail;
        $job->province=$request->provinces;
        $job->district=$request->districts;
        $job->google_map=$request->google_map;

        // dd($job->status_job);
        $job->note=$request->note;
        $job->save();
        $inputs = $request->input('inputs');
        $inputs_edit = $request->input('inputs_edit');
        // dd($inputs_edit);
        // ตรวจสอบว่าได้รับข้อมูล inputs หรือไม่
        $project = Project::where('name',$request->other_detail)->first();
        if (is_array($inputs)) {
            foreach ($inputs as $input) {
                // คุณสามารถบันทึกข้อมูลลงฐานข้อมูล หรือประมวลผลตามที่คุณต้องการได้ที่นี่
                // ตัวอย่างการบันทึกข้อมูลลงในฐานข้อมูล
                    $submit=new Project_submit;
                    if($request->type_a == 'other'){
                        $submit->project_id=$project->id;
                    }else{
                        $submit->project_id=$request->type_a;
                    }
                    $submit->job_id=$job->id;
                    $submit->name=$input;
                    $submit->save();


            }
        }

        if (is_array($inputs_edit)) {
            foreach ($inputs_edit as $input) {
                // คุณสามารถบันทึกข้อมูลลงฐานข้อมูล หรือประมวลผลตามที่คุณต้องการได้ที่นี่
                // ตัวอย่างการบันทึกข้อมูลลงในฐานข้อมูล
                    $submit= Project_submit::where('job_id',$request->id)->first();
                    $submit->name=$input;
                    $submit->save();


            }
        }
        // session()->forget('previous_url');

        $file_detail = "";
        if($request->file('file_detail')){
          foreach($request->file('file_detail') as $key => $value){
              $filename         = $value->getClientOriginalName();
              $extension        = $value->getClientOriginalExtension();
              $size             = $value->getSize();
              $file_detail          .= $key.date('His').$filename;
              $destinationPath  = public_path("/assets/images/job_file/");
              $value->move($destinationPath, $file_detail);

              $val = array(
                'job_id'       => $job->id,
                'file_name'    => $file_detail,
                'path'         => "/assets/images/job_file/",
                'type'         => $extension,
                'role_user' => 'admin',
                'created_at'   => new DateTime,
                'updated_at'   => new DateTime
              );
              DB::table('tb_file_job')->insert($val);
          }
        }
        $previousUrl = session()->get('previous_url');
        if(!empty($previousUrl)){
            return redirect($previousUrl)->with('success',1);

        }else{
            return redirect('/job')->with('success',1);

        }
// return redirect($previousUrl);
    }
    public function delete_job(Request $request){
        $mulimg = DB::table('tb_file_job')->where('job_id',$request->id)->get();
        foreach($mulimg AS $item){
            $destinationPath  = public_path("/assets/images/job_file/").$item->file_name;
            if(is_file($destinationPath)){
                unlink($destinationPath);
            }
        }
        $job=JobModel::destroy($request->id);

        return redirect('/job')->with('success',1);
    }
    public function delete_file(Request $request){
        $img = DB::table('tb_file_job')->where('id',$request->id)->first();
        $direction = public_path("/assets/images/job_file/").$img->file_name;
        if(is_file($direction)){
            unlink($direction);// ลบไฟล์รูป
        }
        DB::table('tb_file_job')->where('id',$request->id)->delete();
        return redirect("/job/edit/$request->job_id")->with('success',1);

    }
    public function getDistricts($province_id)
    {
        $districts = DB::table('thai_amphures')->where('province_id', $province_id)->get();
        return response()->json($districts);
    }

    public function templates(Request $request)
    {
        // ดึงข้อมูล Template ทั้งหมด
        $templates = \App\Models\JobTemplate::orderBy('created_at', 'desc')
                        ->paginate($request->show ?? 10);

        return view('backoffice.job.templates', [
            'templates' => $templates
        ]);
    }

    public function template_create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'estimated_hours' => 'nullable|integer',
        ]);

        $template = new \App\Models\JobTemplate;
        $template->title = $request->title;
        $template->description = $request->description;
        $template->estimated_hours = $request->estimated_hours ?? 0;

        // แปลง Checklist จาก Array เป็น JSON (รับค่ามาเป็น array ชื่อ checklist[])
        // ตัวอย่าง input: <input name="checklist[]" value="Step 1">
        if($request->has('checklist')){
            // กรองค่าว่างออก
            $checklist = array_filter($request->checklist, function($value) {
                return !is_null($value) && $value !== '';
            });
            $template->checklist = array_values($checklist);
        }

        $template->save();

        return redirect()->back()->with('success', 1);
    }

    public function template_delete(Request $request)
    {
        $template = \App\Models\JobTemplate::find($request->id);
        $template->delete();

        return redirect()->back()->with('success', 1);
    }

    public function approvals(Request $request)
    {
        // ดึงงานที่มีสถานะ 'waiting_approval'
        // ควร eager load project และ site มาด้วยเพื่อประสิทธิภาพ
        $approvals = \App\Models\Task::where('status', 'waiting_approval')
                        // ->with(['project', 'site']) // เปิดใช้บรรทัดนี้ถ้าใน Task Model มี Relation แล้ว
                        ->orderBy('updated_at', 'desc')
                        ->paginate($request->show ?? 10);

        return view('backoffice.job.approvals', [
            'approvals' => $approvals
        ]);
    }

    public function approval_action(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'action' => 'required|in:approve,reject',
            'comment' => 'nullable|string'
        ]);

        $task = \App\Models\Task::find($request->task_id);

        // 1. อัปเดตสถานะงาน
        if ($request->action == 'approve') {
            $task->status = 'completed'; // อนุมัติ -> จบงาน
            $status_log = 'approved';
        } else {
            $task->status = 'rejected'; // ปฏิเสธ -> ตีกลับ
            $status_log = 'rejected';
        }
        $task->save();

        $log = new \App\Models\TaskApproval;
        $log->task_id = $task->id;
        $log->approver_id = auth()->guard('admin')->user()->id ?? 1; // ใส่ ID คนกด
        $log->status = $status_log;
        $log->comment = $request->comment;
        $log->approved_at = now();
        $log->save();

        return redirect()->back()->with('success', 1);
    }

    public function logs(Request $request)
    {
        $logs = \App\Models\TaskLog::with(['user', 'task'])
                    ->orderBy('created_at', 'desc')
                    ->paginate($request->show ?? 10);

        return view('backoffice.job.logs', [
            'logs' => $logs
        ]);
    }

}
