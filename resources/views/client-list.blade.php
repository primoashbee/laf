@extends('layouts.app')

@section('content')

<div class="container" id="root">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Client List</div>

                
                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="animated fadeOut alert alert-danger">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <div class="table-container">
                        {{-- <div class="form-inline float-left">
                            <input type="text" class="form-control" placeholder="Client name" aria-label="Client name" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">Search</button>
                            </div>
                        </div>                             --}}
                        @if(\Str::contains(request()->fullUrl(),'/home'))
                        <div class="form-inline float-right">
                            <div class="form-check mb-2 mr-sm-2">
                                <label class="form-check-label" for="inlineFormCheck">
                                    Filter Branch: 
                                </label>

                                <select class="form-control" style="margin-left:10px" id="select_office">
                                    @if(auth()->user()->is_admin)
                                        <option value="MAIN OFFICE"> Main Office </option>
                                        @foreach ($offices as $office)
                                            <option value="{{$office->name}}">{{ $office->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{auth()->user()->office()->first()->name}}">{{ auth()->user()->office()->first()->name}}</option>
                                    @endif   
                                </select>
                              </div>
                        </div>
                        @endif
                        <div class="clearfix"></div>
                        @if(\Str::contains(request()->fullUrl(),'/batch/'))
                        <h3>Branch: {{request()->branch}}</h3>
                        <h3>Date: {{request()->date}}</h3>
                            @if(auth()->user()->level=="MANAGER")
                            <h5>Unit A: {{$clients->where(DB::raw('RIGHT(loan_officer,1)'),'A')->count()}}</h5>
                            <h5>Unit B: {{$clients->where(DB::raw('RIGHT(loan_officer,1)'),'B')->count()}} </h5>
                            @endif
                        @endif
                        <div class="form-inline float-right">
                            <div class="form-check mb-2 mr-sm-2">
                            @if($client_list->count() > 0)  
                             
                                @if(\Str::contains(request()->fullUrl(),'/batch/'))
                            
                                    <a href="{{route('print.list').'?branch='.request()->branch.'&date='.request()->date}}"> <button class="btn btn-primary">Print All</button></a>
                                @endif

                                
                            @endif

                            </div>
                        </div>
                        <table class="table" id="table-list">
                            <thead>
                                <tr>
                                    <td>Branch</td>
                                    <td>Loan Officer</td>
                                    <td>Name</td>
                                    <td>Exported At</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($client_list as $client)
                                        <tr>
                                            <td>{{$client->branch}}</td>
                                            <td>{{$client->loan_officer}}</td>
                                            <td>{{$client->first_name.' '.$client->middle_name.' '.$client->last_name}}</td>
                                            <td>{{$client->timestamp}}</td>
                                            <td>
                                                <a href="/export/{{$client->id}}">
                                                   <i class="btn btn-primary fa fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                @endforeach
                                
                            </tbody>
                        </table>

                        {{-- @if($clients->count() == 0)
                            <h3>No Results Found.</h3>
                        @else
                            <h4># of Clients: {{$clients->total()}}</h4>
                        @endif --}}
                        {{-- {{ $clients->links() }} --}}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script defer>
    window.addEventListener('DOMContentLoaded', function() {
            (function($) {
                $('#table-list').DataTable({
                    "pageLength": 25
                })
                
                @if(request()->has('branch'))
                    $('#select_office').val('{{request()->branch}}')
                @endif

                @if(session()->has('download'))
                    console.log("{{session('download')}}")
                    location.reload()
                @endif
                @if(request()->has('page'))
                    $('#pills-profile-tab').click()
                @endif
                    $('#select_office').change(function(){
                        var selected = $(this).val()
                        var href = window.location.origin + window.location.pathname
                        if(selected=="MAIN OFFICE"){
                            window.location = href;
                        }else{
                            window.location = href+'?branch='+selected
                        }
                        
                    })

            })(jQuery);
        });
    </script>
@endsection 