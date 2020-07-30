@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div style="clear:left;text-align: right;margin-bottom: 20px;">
                        <h4 class="float-left">Clients To Be Imported</h4>
                        <a href="/import" onclick="disable(this);" class="btn btn-primary import" {{$rows->count() > 0 ? '' : 'disabled'}} >Import Clients</a>
                    </div>
                    <div class="table-container">
                        @if(session()->has('message'))
                            <div class="animated fadeOut alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Branch</td>
                                    <td>Name</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $key => $client)
                                    <tr>
                                        <td>{{$client[69]}}</td>
                                        <td>{{$client[1].' '.$client[2].' '.$client[3]}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($rows->count() == 0)
                            <h1>No Results Found.</h1>
                        @endif
                        
                        {{ $rows->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>

function disable(a){
    a.onclick = function(event) {
        event.preventDefault();
     }
}
</script>
@endsection