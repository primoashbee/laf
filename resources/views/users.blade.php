@extends('layouts.app')
@section('content')

<div class="container" id="root">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Users Lists</div>

                
                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="animated fadeOut alert alert-danger">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <div class="table-container">
                        <table class="table" id="table-list">
                            <thead>
                                <tr>
                                    <td>Username</td>
                                    <td>Level</td>
                                    <td>Branch</td>
                                    <td>IP Address</td>
                                    <td style="width: 100px">Last login</td>
                                    <td >Description</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->level}}</td>
                                        <td>{{$user->office()->first()->name}}</td>
                                        <td>{{$user->lastLogin()['ip_address']}}</td>
                                        <td>{{\Carbon\Carbon::parse($user->lastLogin()['created_at'])->diffForHumans()}}</td>
                                        <td>{{$user->lastLogin()['description']}}</td>
                                        <td>
                                            <a href="{{route('user.reset',$user->id)}}"><button class="btn btn-primary">Reset</button></a>
                                            @if($user->disabled)
                                            <a href="{{route('user.enable',$user->id)}}"><button class="btn btn-success">Enable</button></a>
                                            @else
                                            <a href="{{route('user.disable',$user->id)}}"><button class="btn btn-danger">Disable</button></a>
                                            @endif

                                            
                                        </td>
                                    </tr>
                                @endforeach
                               
                            </tbody>
                        </table>
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