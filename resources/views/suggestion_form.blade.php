<div class="row mb-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Bucket Suggestion</h5>                    
            </div>
            <div class="card-body">
            <form id="suggestion-form" method="post" action="{{ route('post.suggestion')}}" enctype="multipart/form-data">
            @csrf

            @if($balls)
                @foreach($balls as $val) 
                <div class="input-group mb-3">
                <span class="input-group-text w-25">{{ $val['name'] }}</span>
                <input type="hidden" name="ball_volume[{{ $val['ball_id'] }}]" value="{{ $val['volume'] }}">
                <input type="hidden" name="ball_name[{{ $val['ball_id'] }}]" value="{{ $val['name'] }}">                
                <input type="number" class="form-control" placeholder="Quantity" id="" name="quantity[{{ $val['ball_id'] }}]" value="{{ old('quantity['.$val['ball_id'].']') }}">
                </div>
                @endforeach
            @endif  
                            
            <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Result</h5>                    
            </div>
            <div class="card-body">
            @if(session()->has('suggestion'))
            <p>Following are the suggested bucket</p>
            <table class="table table-success table-striped">
            <thead>
                <tr>
                <th scope="col">Bucket</th>
                <th scope="col">suggestion</th>                
                </tr>
            </thead>
            <tbody>
                @foreach( session('suggestion') as $valArr)
                <tr>
                <th scope="row">{{ $valArr[0]['bucket_name'] ?? '' }}</th>
                <td>
                    @php 
                    $i = 0; 
                    @endphp

                    @foreach( $valArr as $val)
                        @php 
                        $i++; 
                        @endphp

                        {{ $val['quantity'].' '.$val['ball_name'].' Balls' }} 
                        @if( $i < count($valArr) )
                        and
                        @endif
                    @endforeach
                </td>  
                </tr>    
                @endforeach
            </tbody>
            </table>            
            @endif
            </div>
        </div>
    </div>
</div>

<script>
const form_3 = document.getElementById('suggestion-form')    
const form_3_reqFields = { 
    @foreach($balls as $val) 
    'quantity[{{ $val['ball_id'] }}]': { 'type': 'name', 'title': 'Quantity' }, 
    @endforeach    
}    

Object.entries(form_3_reqFields).forEach(([key, val]) => {
    const type = val.type ?? ''	
    const funcName = 'validate_' + type    
    const myInput = document.querySelector('input[name="'+key+'"]');
    if(myInput){
        myInput.addEventListener("input", (e) => {  
            eval(`${funcName}(key,val)`);
        });
    }
    
});
if(form_3){
    form_3.addEventListener("submit", (e)=>{        
        let error = validate(form_3_reqFields)  
        if(error > 0){
            e.preventDefault()   
            showToaster({'status':'error','message':'Please check required fields.'})    
        }  
        else{
            hideToaster()
        }  
    });
}
</script>