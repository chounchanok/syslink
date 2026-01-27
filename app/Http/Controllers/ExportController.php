<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Admin;
use App\Models\JobModel;
use App\Models\Jobsubmit;
use App\Models\Jobsignature;
use App\Exports\JobExport;
use App\Exports\Job_projectExport;
use App\Models\JobFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Project;
use App\Models\Project_submit;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ExportController extends Controller
{
    public function export_submit(Request $request){
        // dd('a');
        $pdf = PDF::loadView('backoffice.export.pdf.submit_job');
        return $pdf->stream();
    }

    public function team_report(Request $request){
        // dd($request->all());
        // $month_job = $request->month;
        $start_date = $request->start_date; // รับ start_date จาก request
        $end_date = $request->end_date;     // รับ end_date จาก request
        // $sortedArray = collect($start_date,$end_date)->sortBy(function ($item) {
        //     return $item;
        // })->values()->all();
        // $month_job=$sortedArray;
        // return view('backoffice.export.excel.team_report',[
        //     'start_date'=>$start_date,
        //     'end_date'=>$end_date,
        // ]);
        return Excel::download(new JobExport($start_date, $end_date), 'Install_report.xlsx',);
    }

    public function project_report(Request $request){
        // $month = 1;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // แปลงวันที่เป็นรูปแบบ Carbon
        $startDate = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();

        $formattedStartDates = $startDate->format('Y-m-d H:i:s');
        $formattedEndDates = $endDate->format('Y-m-d H:i:s');

        $project = $request->project;

        // ตรวจสอบงานในช่วงวันที่ที่กำหนด
        $check = JobModel::where('type', $project)
            ->whereBetween('install_date', [$formattedStartDates, $formattedEndDates])
            ->where('status_job', 'success')
            ->count();

        if ($check == 0) {
            return redirect('/')->with('error', 'ไม่พบบันทึกงานนี้');
        }

        // ดึงชื่อโปรเจคจากตาราง tb_project
        $project_name = DB::table('tb_project')
            ->where('id', $project)
            ->select('name')
            ->first()
            ->name;

        // ส่งออกไฟล์ Excel
        return Excel::download(new Job_projectExport($start_date, $end_date, $project),
            'Install_' . $project_name . '_report.xlsx');

    }

    public function view_submit($id){
        $job = JobModel::find($id);
        $file_job = JobFile::where('job_id',$id)->get();
        $file_submit = Jobsubmit::where('job_id',$id)->get();
        // dd($file_job);
        // $signature = Jobsignature::where('job_id',$id)->get();
        $project= Project::where('id',$job->type)->first();
        $head=null;
        if(!empty($project)){
            $head= Project_submit::where('project_id', $project->id)
            ->where(function($query) use ($job) {
                $query->where('job_id', $job->id)
                      ->orWhereNull('job_id');
            })
            ->get();
        }

        // dd($head);
        // if($job->type == "Tesla"){
        $sale=User::where('id',$job->sale)->first();
        $engineer=User::where('id',$job->engineer)->first();
        $team=Team::where('id',$job->technician)->first();
        $provinces=DB::table('thai_provinces')->where('id',$job->province)->first();
        $amphures=DB::table('thai_amphures')->where('id',$job->district)->first();

            return view('backoffice.job.view.tesla',[
                'job'=>$job,
                'file_job'=>$file_job,
                'file_submit'=>$file_submit,
                // 'signature'=>$signature,
                'head'=>$head,
                'sale'=>$sale,
                'engineer'=>$engineer,
                'team'=>$team,
                'provinces'=>$provinces,
                'amphures'=>$amphures,
            ]);

    }
    public function album_admin($id){
        $job=JobModel::where('id',$id)->first();
        $album=JobFile::where('job_id',$id)->where('role_user','admin')->get();
        $name='admin';
        return view('backoffice.job.view.album',[
            'album'=>$album,
            'name'=>$name,
            'id'=>$id,
            'job'=>$job,
        ]);
    }
    public function album_sale($id){
        $job=JobModel::where('id',$id)->first();
        $album=JobFile::where('job_id',$id)->where('role_user','sale')->get();
        $name='sale';
        return view('backoffice.job.view.album',[
            'album'=>$album,
            'name'=>$name,
            'id'=>$id,
            'job'=>$job,
        ]);
    }
    public function album_engineer($id){
        $job=JobModel::where('id',$id)->first();
        $album=JobFile::where('job_id',$id)->where('role_user','engineer')->get();
        $name='engineer';
        return view('backoffice.job.view.album',[
            'album'=>$album,
            'name'=>$name,
            'id'=>$id,
            'job'=>$job,
        ]);
    }
}
