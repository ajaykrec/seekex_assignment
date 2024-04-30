<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bucket;
use App\Models\Ball;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    function home(){       
        $meta = [
            'title'=>'Home',
            'keywords'=>'',
            'description'=>'',
        ];   
        
        //== get buckets ==
        $count1 = 1;
        $q = DB::table('buckets');  
        $q->leftJoin('carts', 'carts.bucket_id', '=', 'buckets.bucket_id'); 
        $q->leftJoin('balls', 'balls.ball_id', '=', 'carts.ball_id');       
        $q->select('buckets.*');
        $q->addSelect(DB::raw('sum(carts.quantity * balls.volume) as filled_space'));
        $q->groupBy("buckets.bucket_id");      
        $q->orderBy("buckets.name", "asc");         
        $buckets  = $q->get()->toArray(); 
        $buckets = json_decode(json_encode($buckets), true);
        //p($buckets);

        //== get balls ==
        $count2 = 1;
        $q = Ball::query();        
        $q->orderBy("name", "asc"); 
        $count = $q->count();     
        $balls  = $q->get()->toArray();

        $data = compact('meta','buckets','count1','balls','count2');    
        //p($data);     
        return view('home')->with($data);
    }

    function add_suggestion(Request $request){ 
       
        $quantityArr    = $request['quantity'] ?? [];
        $ballvolumeArr  = $request['ball_volume'] ?? [];
        $ballnameArr    = $request['ball_name'] ?? [];

        //== get available buckets ==       
        $q = DB::table('buckets');  
        $q->leftJoin('carts', 'carts.bucket_id', '=', 'buckets.bucket_id'); 
        $q->leftJoin('balls', 'balls.ball_id', '=', 'carts.ball_id');       
        $q->select('buckets.bucket_id','buckets.name','buckets.volume');
        $q->addSelect(DB::raw('sum(carts.quantity * balls.volume) as filled_space'));
        $q->groupBy("buckets.bucket_id");  
        $buckets = $q->get()->toArray(); 
        $buckets = json_decode(json_encode($buckets), true);

        $total_ball_volume = 0;
        foreach($quantityArr as $key=>$val){           
            $quantity    = $val;
            $ball_volume = $ballvolumeArr[$key];
            $total_ball_volume = $total_ball_volume + $ball_volume*$quantity;
        }    
        $total_bucket_volume = 0;
        foreach($buckets as $val){            
            $total_bucket_volume = $total_bucket_volume + $val['volume'] - $val['filled_space'];            
        }
               
        $filled_Arr  = [];        
        $ballFillArr = [];
        if($buckets){
            foreach($buckets as $val){   
                $bucket_id     = $val['bucket_id'];
                $bucket_name   = $val['name'];
                $bucket_volume = $val['volume'] - $val['filled_space'];

                foreach($quantityArr as $key=>$qval){
                    $ball_id     = $key;
                    $quantity    = $qval;
                    $ball_volume = $ballvolumeArr[$key];
                    $ball_name   = $ballnameArr[$key];

                    for($i=1;$i<=$quantity;$i++){
                        $bAr = $ballFillArr[$ball_id] ?? []; 
                        if($bucket_volume>=$ball_volume && $total_ball_volume && $quantity > count($bAr) ){                           
                            $filled_Arr[$bucket_id][$ball_id][] = [
                                'bucket_id'=>$bucket_id,
                                'bucket_name'=>$bucket_name,
                                'ball_id'=>$ball_id,
                                'ball_name'=>$ball_name,
                                'quantity'=>1
                            ];
                            $bucket_volume = $bucket_volume - $ball_volume;
                            $total_ball_volume = $total_ball_volume - $ball_volume;
                            $total_bucket_volume = $total_bucket_volume - $ball_volume;
                            $ballFillArr[$ball_id][] = 1;
                        }                                              
                    }
                }
            }
        }

        if($filled_Arr){
            $returnArr = [];            
            foreach($filled_Arr as $bucket_id=>$val){                
                foreach($val as $ball_id=>$val2){                    
                    $quantity = count($val2);
                    foreach($val2 as $val3){ 
                        $bucket_name = $val3['bucket_name'] ?? '';
                        $ball_name = $val3['ball_name'] ?? '';
                    }
                    $returnArr[$bucket_id][] = [
                        'bucket_id'=>$bucket_id,
                        'ball_id'=>$ball_id,
                        'bucket_name'=>$bucket_name,
                        'ball_name'=>$ball_name,
                        'quantity'=>$quantity,
                    ];     
                }                         
            }

            //== save data in table
            foreach($returnArr as $key=>$valArr){  
                foreach($valArr as $key=>$val){   
                    $table = new Cart;
                    $table->bucket_id = $val['bucket_id'];
                    $table->ball_id   = $val['ball_id'];
                    $table->quantity  = $val['quantity'];                    
                    $table->save();
                }  
            }
            //== redirect
            return redirect( route('home') )
            ->with('success','Suggestion addedd successfully')
            ->with(['suggestion'=>$returnArr]);
        }
        else{
            return back()->withInput()->with('error','No Space Available'); 
        }           
    }    
}
