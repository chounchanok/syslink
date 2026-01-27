<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Project_submit;
use Illuminate\Support\Facades\Validator;
class ProjectController extends Controller
{
    public function index(Request $request){
        if(!empty($request->show)){
            $project=Project::whereNull('type')->where('id','!=',39)->orderBy('created_at', 'desc')->paginate($request->show);
        }else{
            $project=Project::whereNull('type')->where('id','!=',39)->orderBy('created_at', 'desc')->paginate(10);
        }
        // $project_submit=Project_submit::where('project_id',$request->id)->whereNull('job_id')->get();
        return view('backoffice.project.index',[
            'project'=>$project,
            'show'=>$request->show
        ]);

    }
    public function create(Request $request){
         $validator = Validator::make($request->all(), [
        'name'  => 'required|string|max:255|unique:tb_project,name',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
        $project = new Project;
        $project->name=$request->name;
        $project->color=$request->color;
        $project->save();
        return redirect('project');
    }
    public function edit($id){
        $project=Project::find($id);
        return view('backoffice.project.edit',[
            'project'=>$project
        ]);
    }
    public function update(Request $request){
         $validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255|unique:tb_project,name,' . $request->id,
]);

        $project =  Project::find($request->id);
        $project->name=$request->name;
        $project->color=$request->color;
        $project->save();
        return redirect('/project');
    }
    public function add_submit(Request $request,$id){
        $project=Project::find($id);
        if(!empty($request->show)){
            $submit=Project_submit::where('project_id',$id)->whereNull('job_id')->paginate($request->show);
        }else{
            $submit=Project_submit::where('project_id',$id)->whereNull('job_id')->paginate(10);
        }
        // $submit=Project_submit::where('project_id',$id)->get();
        return view('backoffice.project.add',[
            'project'=>$project,
            'submit'=>$submit,
            'id'=>$id,
            'show'=>$request->show
        ]);
    }
    public function submit_create(Request $request){
        // $check = Project_submit
        $submit= new Project_submit;
        $submit->project_id=$request->project_id;
        $submit->name=$request->name;
        $submit->save();
        return redirect(route('project.add',$request->project_id));
    }
    public function submit_edit($id){
        $submit=Project_submit::find($id);
        return view('backoffice.project.edit_submit',[
            'submit'=>$submit
        ]);
    }
    public function submit_update(Request $request){
        $submit = Project_submit::find($request->id);
        // dd($submit)
        $submit->name=$request->name;
        $submit->save();
        return redirect("/project/submit/$submit->project_id")->with('success',1);
    }

    public function delete(Request $request){
        $project=Project::destroy($request->id);
        $submit=Project_submit::where('project_id',$request->id)->delete();
        return redirect(route('project'))->with('success',1);
    }
    public function submit_delete(Request $request){
        $submit=Project_submit::destroy($request->id);
        if(!empty($request->url_return)){
            return redirect($request->url_return)->with('success',1);
        }
        return redirect()->back()->with('success',1);

    }

    public function search(Request $request){
        $project=Project::where('name','like','%'.$request->search.'%')->whereNull('type')->where('id','!=',39)->orderBy('created_at', 'desc')->paginate(10);
        return view('backoffice.project.index',[
            'project'=>$project,
            'show'=>$request->show,
            'search'=>$request->search
        ]);
    }
}
