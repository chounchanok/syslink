<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_submit;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function index(Request $request){
        $product=Product::orderBy('created_at', 'desc')->paginate($request->show);
        return view('backoffice.product.index',[
            'product'=>$product,
            'show'=>$request->show
        ]);

    }
    public function create(Request $request){
         $validator = Validator::make($request->all(), [
        'name'  => 'required|string|max:255|unique:tb_product,name',
        'sku'   => 'required|string|max:100|unique:tb_product,sku',
        'unit'  => 'required|string|max:50',
        'qty'   => 'required|integer|min:0'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
        $product = new Product;
        $product->name=$request->name;
        $product->sku=$request->sku;
        $product->unit=$request->unit;
        $product->qty=$request->qty;
        $product->save();
        return redirect('product');
    }
    public function edit($id){
        $product=Product::find($id);
        return view('backoffice.product.edit',[
            'product'=>$product
        ]);
    }
    public function update(Request $request){
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tb_product,name,' . $request->id,
            'sku'   => 'string|max:100|unique:tb_product,sku',
            'unit'  => 'string|max:50',
            'qty'   => 'integer|min:0'
        ]);

        dd($request->all());

        $product =  Product::find($request->id);
        $product->name=$request->name;
        $product->sku=$request->sku;
        $product->unit=$request->unit;
        $product->qty=$request->qty;
        $product->save();
        return redirect('/product');
    }
    public function add_submit(Request $request,$id){
        $product=Product::find($id);
        if(!empty($request->show)){
            $submit=Product_submit::where('product_id',$id)->whereNull('job_id')->paginate($request->show);
        }else{
            $submit=Product_submit::where('product_id',$id)->whereNull('job_id')->paginate(10);
        }
        // $submit=Product_submit::where('product_id',$id)->get();
        return view('backoffice.product.add',[
            'product'=>$product,
            'submit'=>$submit,
            'id'=>$id,
            'show'=>$request->show
        ]);
    }
    public function submit_create(Request $request){
        // $check = Product_submit
        $submit= new Product_submit;
        $submit->product_id=$request->product_id;
        $submit->name=$request->name;
        $submit->save();
        return redirect(route('product.add',$request->product_id));
    }
    public function submit_edit($id){
        $submit=Product_submit::find($id);
        return view('backoffice.product.edit_submit',[
            'submit'=>$submit
        ]);
    }
    public function submit_update(Request $request){
        $submit = Product_submit::find($request->id);
        // dd($submit)
        $submit->name=$request->name;
        $submit->save();
        return redirect("/product/submit/$submit->product_id")->with('success',1);
    }

    public function delete(Request $request){
        $product=Product::destroy($request->id);
        $submit=Product_submit::where('product_id',$request->id)->delete();
        return redirect(route('product'))->with('success',1);
    }
    public function submit_delete(Request $request){
        $submit=Product_submit::destroy($request->id);
        if(!empty($request->url_return)){
            return redirect($request->url_return)->with('success',1);
        }
        return redirect()->back()->with('success',1);

    }

    public function search(Request $request){
        $product=Product::where('name','like','%'.$request->search.'%')->whereNull('type')->where('id','!=',39)->orderBy('created_at', 'desc')->paginate(10);
        return view('backoffice.product.index',[
            'product'=>$product,
            'show'=>$request->show,
            'search'=>$request->search
        ]);
    }
}
