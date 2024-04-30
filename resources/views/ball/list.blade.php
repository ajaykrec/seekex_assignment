<div class="row mb-5">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header d-flex justify-content-between">
                    <h5>Balls</h5>
                    <div class="me-3"><a href="{{ route('ball.create') }}" class="btn btn-primary btn-sm">Add Ball</a></div>
                </div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col" class="text-center">Volume (in Inches)</th>
                                <th scope="col" width="12%">option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($balls)
                                @foreach($balls as $val)
                                <tr id="ballrow-{{ $val['ball_id'] }}">
                                    <th scope="row">{{ $count2 }}</th>
                                    <td>{{ $val['name'] }}</td>
                                    <td class="text-center">{{ $val['volume'] }}</td>
                                    <td>
                                        <button data-url="{{ route('ball.destroy',$val['ball_id']) }}" class="btn btn-sm btn-danger delete" data-id="{{ $val['ball_id'] }}">Delete</button>
                                        <a href="{{ route('ball.edit',$val['ball_id']) }}" class="btn btn-sm btn-success">Edit</a>
                                    </td>
                                </tr>
                                @php $count2++; @endphp
                                @endforeach
                            @else
                            <tr>
                            <td colspan="4">No Record Found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    $('.delete').on('click',(e)=>{
        let id = e.target.getAttribute('data-id');
        let url = e.target.getAttribute('data-url');
        $.ajax({
            type: "delete",
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            dataType: "json",
            url: url, 
            success: function(response){  
                $('#ballrow-'+id).hide()                   
                showToaster({'status':'success','message':response.message})    
            }
        });  
    })
    </script>
