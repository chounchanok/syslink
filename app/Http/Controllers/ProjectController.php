<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Project_submit;
use App\Models\Site;
use Illuminate\Support\Facades\Validator;
class ProjectController extends Controller
{
    public function index(Request $request){
        $query = Project::query();

        // Search Logic
        if(!empty($request->search)){
            $query->where('name','like','%'.$request->search.'%')
                  ->orWhere('code','like','%'.$request->search.'%');
        }

        // Pagination
        $limit = $request->show ?? 10;
        $project = $query->orderBy('created_at', 'desc')->paginate($limit);

        return view('backoffice.project.index',[
            'project' => $project,
            'show' => $limit,
            'search' => $request->search
        ]);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:projects,code', // เพิ่ม Code (PO/Job No.)
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = new Project;
        $project->name = $request->name;
        $project->code = $request->code;
        $project->color = $request->color ?? '#3b82f6';
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->status = 'planned'; // Default status
        $project->save();

        return redirect('project')->with('success', 1);
    }

    public function edit($id){
        $project = Project::find($id);
        return view('backoffice.project.edit',[
            'project' => $project
        ]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:projects,code,' . $request->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $project = Project::find($request->id);
        $project->name = $request->name;
        $project->code = $request->code;
        $project->color = $request->color;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->status = $request->status; // Update status
        $project->save();

        return redirect('/project')->with('success', 1);
    }

    public function delete(Request $request){
        $project = Project::destroy($request->id);
        // ลบ Site ที่เกี่ยวข้องด้วย (Cascade logic ควรอยู่ใน Model หรือ Database แต่ใส่ตรงนี้กันเหนียว)
        Site::where('project_id', $request->id)->delete();
        return redirect(route('project'))->with('success', 1);
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

    // --- ส่วนจัดการ Site (เดิมคือ add_submit) ---

    public function sites(Request $request, $id = null) // 1. กำหนด default เป็น null
    {
        $limit = $request->show ?? 10;

        if ($id) {
            // กรณีมี ID: ดู Site ของโปรเจกต์นั้นๆ (เหมือนเดิม)
            $project = Project::find($id);
            $sites = Site::where('project_id', $id)->paginate($limit);
        } else {
            // กรณีไม่มี ID: ดู Site ทั้งหมดในระบบ (มาจากการกดเมนู Sidebar)
            $project = null; // ไม่มีโปรเจกต์หลัก
            $sites = Site::with('project')->paginate($limit); // ดึงข้อมูลโปรเจกต์มาด้วย
        }

        return view('backoffice.project.sites',[
            'project' => $project,
            'sites' => $sites,
            'id' => $id,
            'show' => $limit
        ]);
    }

    public function site_create(Request $request){
        $site = new Site;
        $site->project_id = $request->project_id;
        $site->name = $request->name;
        $site->address = $request->address;
        $site->lat = $request->lat;
        $site->lng = $request->lng;
        $site->save();

        return redirect(route('project.sites', $request->project_id))->with('success', 1);
    }

    public function site_delete(Request $request){
        $site = Site::find($request->id);
        $project_id = $site->project_id;
        $site->delete();

        return redirect(route('project.sites', $project_id))->with('success', 1);
    }

    public function assets(Request $request)
    {
        // ดึงรายการ Asset ทั้งหมด พร้อมข้อมูล Project และ Product
        $assets = \App\Models\ProjectAsset::with(['project', 'product'])
                    ->orderBy('created_at', 'desc')
                    ->paginate($request->show ?? 10);

        // เตรียมข้อมูลสำหรับ Dropdown ในฟอร์มเพิ่ม Asset
        $projects = Project::where('status', '!=', 'completed')->get(); // เลือกเฉพาะโครงการที่ยังไม่จบ
        $products = \App\Models\Product::all(); // ดึงสินค้าจาก Master Data (Inventory)

        return view('backoffice.project.assets', [
            'assets' => $assets,
            'projects' => $projects,
            'products' => $products
        ]);
    }

    public function asset_create(Request $request)
    {
        $request->validate([
            // แก้จาก projects เป็น tb_project
            'project_id' => 'required|exists:tb_project,id',

            // ตรวจสอบชื่อตารางสินค้าด้วยครับ (ถ้าพี่ใช้ tb_product ก็ต้องแก้ตรงนี้ด้วย)
            'product_id' => 'required|exists:tb_product,id',

            'quantity' => 'required|integer|min:1',
        ]);

        $asset = new \App\Models\ProjectAsset;
        $asset->project_id = $request->project_id;
        $asset->product_id = $request->product_id;
        $asset->quantity = $request->quantity;
        $asset->status = 'pending_install';
        $asset->save();

        return redirect()->back()->with('success', 1);
    }

    public function asset_update_status(Request $request)
    {
        // ใช้สำหรับช่างกดอัปเดตสถานะ เช่น ติดตั้งเสร็จแล้ว
        $asset = \App\Models\ProjectAsset::find($request->id);
        $asset->status = $request->status;

        if($request->status == 'installed'){
            $asset->installed_at = now();
        }

        $asset->save();
        return redirect()->back()->with('success', 1);
    }

    public function asset_delete(Request $request)
    {
        $asset = \App\Models\ProjectAsset::find($request->id);
        $asset->delete();
        return redirect()->back()->with('success', 1);
    }
}
