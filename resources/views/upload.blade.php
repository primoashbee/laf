@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{route('form.upload')}}" method="POST" enctype="multipart/form-data">
                        <div class="custom-file">
                            @csrf
                            <input type="file" class="custom-file-input"  name="uploadFile"  accept="" required id="customFile">
                            {{-- <input type="file" class="custom-file-input"  name="uploadFile"  accept=".xlsx, csv" required id="customFile"> --}}
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <button class="btn btn-success mt-2" type="submit">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>

$('#customFile').on('change',function(){
//get the file name
var fileName = $(this).val();
//replace the "Choose a file" label
$(this).next('.custom-file-label').html(fileName);
})
</script>
@endsection