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
                        <div class="alert alert-danger" role="alert">
                            {{ session()->get('message') }}
                        </div>
                        @endif
                        <div class="d-inline-block" style="width:100%">
                             <div class="wrapper float-left">
                                    <div class="d-inline-block mb-2 mr-sm-2">
                                        <label class="form-check-label" for="inlineFormCheck">
                                            Filter Level: 
                                        </label>
                                        <?php 
										$value = null;
                                    
										if(request()->has('office_id')){
											$value = App\Office::select('id','name')->find(request()->office_id)->officeSelectValue();    
										}
                                        
										?>
										
                                        <office-list  default_value="{{request()->has('office_id') ? $value : '' }}"  style="width:360px" ></office-list>
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
                                        <input type="hidden" value="{{request()->office_id}}" name="office_id" >
                                        <input type="hidden" value="{{request()->date}}" name="date">
                                        <input type="hidden" value="{{request()->search}}" name="search">
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
                                    <td>LO</td>
                                    <td>Created At</td>
                                    <td>Created By</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                        <tr>
                                            <td>{{$client->office->name}}</td>
                                            <td>{{$client->first_name.' '.$client->middle_name.' '.$client->last_name}}</td>
                                            <td>{{$client->loan_officer}}</td>
                                            <td>{{$client->created_at->format('F d, Y')}}</td>
                                            <td>{{$client->user->name}}</td>
                                            <td>
                                                <a href="{{route('client.export',$client->id)}}">
                                                    <button class="btn btn-success" type="button">
                                                        <i class="fa fa-download"></i>
                                                    </button>
                                                </a>
                                                <a href="{{route('client.update',$client->id)}}">
                                                    <button class="btn btn-primary" type="button">
                                                        <i class=" fa fa-edit"></i>
                                                    </button>
                                                </a>
                                                <a href="{{route('client.delete',$client->id)}}">
                                                    <button class="btn btn-danger" type="button">
                                                        <i class=" fa fa-trash"></i>
                                                    </button>
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