<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Ball;
use Illuminate\Support\Facades\DB;

class BallController extends Controller
{
    public function index(Request $request){        
      //===
    }    
    public function create(){        
        $meta = [
            'title'=>'Add Ball',
            'keywords'=>'',
            'description'=>'',
        ];          
        $data = compact('meta'); 
        return view('ball.create')->with($data);
    }    
    public function store(Request $request){  
        $rules = [
            'name' => 'required|unique:balls', 
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
            $table = new Ball;
            $table->name        = $request['name'];
            $table->volume      = $request['volume'];           
            $table->save();
            // redirect
            return redirect( route('home') )->with('success','Ball created successfully');
        }        
    }
    public function show($id){
        //==
    }    
    public function edit($id){       
        $data = Ball::find($id); 
        if(!$data){
            return redirect( route('home') );
        }
        $data = $data->toArray();

        $meta = [
            'title'=>'Edit Ball',
            'keywords'=>'',
            'description'=>'',
        ];          
        $data = compact('meta','data','id');         
        return view('ball.edit')->with($data);
    }    
    public function update(Request $request, $id){       
        $rules = [
            'name'   => 'required|unique:balls,name,'.$id.',ball_id', 
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
            $table = Ball::find($id);
            $table->name    = $request['name'];
            $table->volume  = $request['volume'];           
            $table->save();           
            return redirect( route('home') )->with('success','Ball updated successfully');
        }
    }
   
    public function destroy($id){       
        $table = Ball::find($id);
        $table->delete();

        DB::table('carts')->where('ball_id', '=', $id)->delete();

        return json_encode(array(
            'status'=>'success',
            'message'=>'Ball deleted successfully'
        ));
        exit;        
    }
}
