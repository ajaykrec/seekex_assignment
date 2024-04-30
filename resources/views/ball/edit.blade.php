@extends('layout.main')

@section('page-content')
<div class="container">
<div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                <h5>Edit Ball</h5>                    
                </div>
                <div class="card-body">
                <form id="ball-form" method="post" action="{{ route('ball.update',$id)}}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="mb-3">
                <label class="form-label">Ball Name</label>
                <input type="text" class="form-control" placeholder="" name="name" value="{{ old('name',$data['name'] ?? '') }}"> 
                <span class="err" id="error-name">
                @error('name')
                {{$message}}
                @enderror 
                </span>                 
                </div> 
                <div class="mb-3">
                <label class="form-label">Volume (in Inches)</label>
                <input type="number" class="form-control" placeholder="" name="volume" value="{{ old('volume', $data['volume'] ?? '') }}"> 
                <span class="err" id="error-volume">
                @error('volume')
                {{$message}}
                @enderror 
                </span>                 
                </div> 
                
                <a href="{{ route('home') }}" class="btn btn-secondary"><< Back</a>                    
                <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


