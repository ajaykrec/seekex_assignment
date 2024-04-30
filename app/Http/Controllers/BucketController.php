<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Bucket;
use Illuminate\Support\Facades\DB;

class BucketController extends Controller
{
    public function index(Request $request){        
      //===
    }    
    public function create(){        
        $meta = [
            'title'=>'Add Bucket',
            'keywords'=>'',
            'description'=>'',
        ];          
        $data = compact('meta'); 
        return view('bucket.create')->with($data);
    }    
    public function store(Request $request){  
        $rules = [
            'name' => 'required|unique:buckets', 
            'volume' => 'required',            
        ];
        $messages = [];
        $validation = Validator::make( 
            $request->toArray(), 
            $rules, 
            $messages
        );
        
        if($validation->fails()) {            
            return back()->withInput()->withErrors($validation->messages());            
        }
        else{
            // store
            $table = new Bucket;
            $table->name        = $request['name'];
            $table->volume      = $request['volume'];           
            $table->save();
            // redirect
            return redirect( route('home') )->with('success','Bucket created successfully');
        }        
    }
    public function show($id){
        //==
    }    
    public function edit($id){       
        $data = Bucket::find($id); 
        if(!$data){
            return redirect( route('home') );
        }
        $data = $data->toArray();

        $meta = [
            'title'=>'Edit Bucket',
            'keywords'=>'',
            'description'=>'',
        ];          
        $data = compact('meta','data','id');         
        return view('bucket.edit')->with($data);
    }    
    public function update(Request $request, $id){       
        $rules = [
            'name'   => 'required|unique:buckets,name,'.$id.',bucket_id', 
            'volume' => 'required',                
        ];
        $messages = [];
        $validation = Validator::make( 
            $request->toArray(), 
            $rules, 
            $messages
        );
        
        if($validation->fails()) {            
            return back()->withInput()->withErrors($validation->messages());            
        }
        else{
            $table = Bucket::find($id);
            $table->name    = $request['name'];
            $table->volume  = $request['volume'];           
            $table->save();           
            return redirect( route('home') )->with('success','Bucket updated successfully');
        }
    }
   
    public function destroy($id){       
        $table = Bucket::find($id);
        $table->delete();

        DB::table('carts')->where('bucket_id', '=', $id)->delete();

        return json_encode(array(
            'status'=>'success',
            'message'=>'Bucket deleted successfully'
        ));
        exit;        
    }
}
