@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="animated fadeOut alert alert-danger">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#for-export" role="tab" aria-controls="pills-home" aria-selected="true">For Export</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#exported" role="tab" aria-controls="pills-profile" aria-selected="false">Exported</a>
                      </li>
                    </ul>
                    <div class="tab-content mt-50" id="pills-tabContent">
                      <div class="tab-pane fade show active" id="for-export" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div style="clear:left;text-align: right;margin-bottom: 20px;">
                            <h4 class="float-left">For Export Partner Clients</h4>
                            <a href="/export?exported=false" {{$for_export > 0 ? '' : 'disabled'}} class="btn btn-primary">Export Client</a>
                        </div>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Branch</td>
                                        <td>Name</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        @if($client->received == false)
                                        <tr>
                                            <td>{{$client->branch}}</td>
                                            <td>{{$client->first_name.' '.$client->middle_name.' '.$client->last_name}}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            @if($for_export == 0)
                                <h3>No Results Found.</h3>
                            @endif
                            {{ $clients->links() }}
                        </div>
                      </div>
                      <div class="tab-pane fade" id="exported" role="tabpanel" aria-labelledby="pills-exported-tab">
                        <div style="clear:left;text-align: right;margin-bottom: 20px;">
                            <h4 class="float-left">Exported Partner Clients</h4>
                            <a href="/export?exported=true" {{$exported > 0 ? '' : 'disabled'}} class="btn btn-primary">Export All</a>
                        </div>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Branch</td>
                                        <td>Name</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        @if($client->received == true)
                                            <tr>
                                                <td>{{$client->branch}}</td>
                                                <td>{{$client->first_name.' '.$client->middle_name.' '.$client->last_name}}</td>
                                                <td>
                                                    <a href="/export/{{$client->id}}">
                                                       <i class="btn btn-primary fa fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            @if($exported == 0)
                                <h3>No Results Found.</h3>
                            @endif
                            {{ $clients->links() }}
                        </div>
                      </div>
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