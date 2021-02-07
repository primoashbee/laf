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
                             <div class="wrapper float-left">
                                    <div class="d-inline-block mb-2 mr-sm-2">
                                        <label class="form-check-label" for="inlineFormCheck">
                                            Filter Branch: 
                                        </label>
                                        <select class="form-control" name="office_id" id="office_id" required>
                                            <option value=""> Please Select </option>
                                            @foreach ($user_offices as $office)
                                                <option value="{{$office->id}}">{{ $office->name }}</option>
                                            @endforeach
                                            
                                        </select>
                                        
                                    </div>
                                    <div class="d-inline-block date-group">
                                        <label for="from_date" class="form-check-label">
                                        From Date
                                        </label>
                                        <input type="date" class="form-control mb-4" id="date" name="date" required>
                                    </div>
                                     @error('date')
                                        <div class="invalid-danger">{{ $message }}</div>
                                    @enderror   
                                    <div>
                                        <button class="btn btn-primary" type="submit" id="fetch"> Filter</button>
                                    </div>
                            </div>  
                            
                            <div class="float-right">

                                @if($clients->count() > 0)  
                                    <form action="{{route('download.list')}}" method = "POST">
                                        {{csrf_field()}}
                                        <button class="btn btn-success" type="submit"> Export Clients</button> 
                                        <input type="text" value="{{request()->office_id}}" name="office_id">
                                        <input type="text" value="{{request()->date}}" name="date">
                                        <input type="text" value="{{request()->search}}" name="search">
                                    </form>
                                @endif
                                
                                    
                                
                            </div>
                            
                        </div>
                        <div>
                            @if($clients->count() > 0)  
                            <div class="form-inline form-group">
                                <input type="text" class="form-control" id="search" required name="search"
                                    placeholder="Search Clients">
                                    <button type="submit" id="btn_search" class="btn btn-primary ml-2">
                                        Search
                                    </button>
                            </div>
                            @endif

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
                                            <td>{{$client->office->name}}</td>
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
            
                
                @if(request()->has('office_id'))
                    $('#office_id').val('{{request()->office_id}}')
                @endif
                @if(request()->has('office_id'))
                    $('#date').val('{{request()->date}}')
                @endif
                @if(request()->has('search'))
                    $('#search').val('{{request()->search}}')
                @endif

                @if(session()->has('download'))
                    console.log("{{session('download')}}")
                    location.reload()
                @endif

                @if(request()->has('page'))
                    $('#pills-profile-tab').click()
                @endif
                    
                    $('#btn_search').click(function(){
                        
                        var selected = $('#office_id').val()
                        var from_date = $('#date').val()
                        var search = $('#search').val()
                        var href = window.location.origin + window.location.pathname
                        if(selected == "" || from_date == "" || search == ""){
                            return false;
                        }
                        return window.location = href+'?office_id='+selected+'&date='+from_date+'&search='+search; 
                        
                    })

                    $('#fetch').click(function(){
                        var selected = $('#office_id').val()
                        var from_date = $('#date').val()
                        var href = window.location.origin + window.location.pathname
                        if(selected == "" || from_date == ""){
                            return false;
                        }

                        return window.location = href+'?office_id='+selected+'&date='+from_date
                        
                    })
            });
        
    </script>
@endsection