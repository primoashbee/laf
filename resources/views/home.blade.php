@extends('layouts.app')

@section('content')

<div class="container" id="root">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="h4">Client List</h4>
                </div>
                <div class="card-body">
                    <div class="table-container">
                         @if(session()->has('message'))
                        <div class="animated fadeOut alert alert-warning">
                            {{ session()->get('message') }}
                        </div>
                        @endif                     
                        <div class="d-inline-block" style="width:100%">
                          <!--   <div class="form-inline float-left mb-2 mr-sm-2">
                                <label class="form-check-label" for="inlineFormCheck">
                                    Filter Branch: 
                                </label>
                                <select class="form-control" style="margin-left:10px" name="branch" id="select_office">
                                    
                                    @if(auth()->user()->is_admin)
                                    <option value="MAIN OFFICE"> Main Office </option>
                                    @foreach ($offices as $office)
                                    <option value="{{$office->name}}">{{ $office->name }}</option>
                                    @endforeach
                                    @endif
                                    <option value="{{ Auth::user()->office->first()->name }}">{{ Auth::user()->office->first()->name }}</option>
                                </select>
                                
                            </div> -->
                             <div class="wrapper float-left">
                                    <div class="d-inline-block mb-2 mr-sm-2">
                                        <label class="form-check-label" for="inlineFormCheck">
                                            Filter Branch: 
                                        </label>
                                        <select class="form-control" name="branch" id="select_office">
                                            
                                            @if(auth()->user()->is_admin)
                                            <option value=""> Main Office </option>
                                            @foreach ($offices as $office)
                                            <option value="{{$office->name}}">{{ $office->name }}</option>
                                            @endforeach
                                            @endif
                                            <option value="{{ Auth::user()->office->first()->name }}">{{ Auth::user()->office->first()->name }}</option>
                                        </select>
                                        
                                    </div>
                                    <div class="d-inline-block date-group">
                                        <label for="from_date" class="form-check-label">
                                        From Date
                                        </label>
                                        <input type="date" class="form-control mb-4" id="from_date" name="from_date">
                                    </div>
                                     @error('from_date')
                                        <div class="animated fadeOut invalid-danger">{{ $message }}</div>
                                    @enderror
                                    <div>
                                        <button class="btn btn-primary" id="filter_client">Filter</button>
                                    </div>
                            </div>  
                            <div class="float-right">

                                @if($clients->count() > 0)  
                                    @if(\Str::contains(request()->fullUrl(),'/home'))
                                        @if(request()->has('from_date'))
                                            @if(request()->has('branch'))
                                                <a href="{{ route('download.list').'?branch='.request()->branch.'&from_date='.request()->from_date}}" class="btn btn-primary">Export Clients</a>
                                            @else
                                                <a href="{{ route('download.list').'&from_date='.request()->from_date}}" class="btn btn-primary">Export Clients</a>    
                                            @endif
                                        @else
                                            @if(request()->has('branch'))
                                                <a href="{{ route('download.list').'?branch='.request()->branch}}" class="btn btn-primary">Export Clients</a>
                                            @else    
                                                <a href="{{ route('download.list')}}" class="btn btn-primary">Export Clients</a>
                                            @endif    

                                        @endif        
                                    @endif
                                @endif
                                
                                    
                                
                            </div>
                            
                        </div>
                        <div>
                            <!-- <form role="search" id="form-search">
                                {{ csrf_field() }} -->
                            <div class="form-inline form-group">
                                <input type="text" class="form-control" id="search" required name="search"
                                    placeholder="Search Clients">
                                    <button type="submit" id="btn_search" class="btn btn-primary ml-2">
                                        Search
                                    </button>
                            </div>
                            @error('search')
                                <strong class="invalid-danger">{{ $message }}</strong>
                            @enderror

                            <!-- </form> -->
                        </div>          
                        <table class="table table-bordered table-striped" id="table-list">
                            <thead>
                                <tr>
                                    <td>Branch</td>
                                    <td>Name</td>
                                    <td>Created At</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                        <tr>
                                            <td>{{$client->branch}}</td>
                                            <td>{{$client->first_name.' '.$client->middle_name.' '.$client->last_name}}</td>
                                            <td>{{$client->created_at->format('F d, Y')}}</td>
                                            <td>
                                                <a href="/export/{{$client->id}}">
                                                   <i class="btn btn-primary fa fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                @endforeach
                                
                            </tbody>
                        </table>

                        <div class="d-inline-block" style="width:100%;">
                            <div class="float-left">
                            @if($clients->count() == 0)
                                <h3>No Results Found.</h3>
                            @else
                                <p>Showing  {{ $clients->firstItem() . ' - '. $clients->lastItem(). ' of '. $clients->total() }} </p>
                            @endif 
                            </div>
                            <div class="float-right">
                            {{ $clients->withQueryString()->links() }} 
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
<script defer>
    window.addEventListener('DOMContentLoaded', function() {
            
                
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
                    // $('#select_office').change(function(){
                    //     var selected = $(this).val()
                    //     var href = window.location.origin + window.location.pathname
                    //     if(selected=="MAIN OFFICE"){
                    //         window.location = href;
                    //     }else{
                    //         window.location = href+'?branch='+selected
                    //     }
                    // })
                    $('#btn_search').click(function(){
                        var search = $('#search').val()
                        var href = window.location.origin + window.location.pathname
                        if (search == '') {
                            window.location = href;
                        }else{
                            window.location = href+'?search='+search;
                        }
                        
                    })

                    $('#filter_client').click(function(){
                        var selected = $('#select_office').val()
                        var from_date = $('#from_date').val()
                        var href = window.location.origin + window.location.pathname

                        if (from_date == '') {
                            if(selected==""){
                              window.location = href;
                            }else{
                                window.location = href+'?branch='+selected
                            } 
                        }else{
                            window.location = href+'?branch='+selected+'&from_date='+from_date;
                        }
                        
                    })
            })(jQuery);
        
    </script>
@endsection