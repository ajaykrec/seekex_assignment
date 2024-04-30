<div class="row mb-5">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header d-flex justify-content-between">
                    <h5>Buckets</h5>
                    <div class="me-3"><a href="{{ route('bucket.create') }}" class="btn btn-primary btn-sm">Add Bucket</a></div>
                </div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col" class="text-center">Volume (in Inches)</th>
                                <th scope="col" class="text-center">Empty Space (in Inches)</th>
                                <th scope="col" width="12%">option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($buckets)
                                @foreach($buckets as $val)
                                <tr id="row-{{ $val['bucket_id'] }}">
                                    <th scope="row">{{ $count1 }}</th>
                                    <td>{{ $val['name'] }}</td>
                                    <td class="text-center">{{ $val['volume'] }}</td>
                                    <td class="text-center">{{ $val['volume'] - $val['filled_space'] }}</td>
                                    <td>
                                        <button data-url="{{ route('bucket.destroy',$val['bucket_id']) }}" class="btn btn-sm btn-danger delete" data-id="{{ $val['bucket_id'] }}">Delete</button>
                                        <a href="{{ route('bucket.edit',$val['bucket_id']) }}" class="btn btn-sm btn-success">Edit</a>
                                    </td>
                                </tr>
                                @php $count1++; @endphp
                                @endforeach
                            @else
                            <tr>
                            <td colspan="5">No Record Found.</td>
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
                $('#row-'+id).hide()                   
                showToaster({'status':'success','message':response.message})    
            }
        });  
    })
    </script>
