@extends('layouts.app')

@section('content')
<div class="container" id="root">
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
                    <div class="table-container">
                        {{-- <div class="form-inline float-left">
                            <input type="text" class="form-control" placeholder="Client name" aria-label="Client name" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">Search</button>
                            </div>
                        </div>                             --}}
{{-- 
                        <div class="form-inline float-right">
                            <div class="form-check mb-2 mr-sm-2">
                                <label class="form-check-label" for="inlineFormCheck">
                                    Filter Branch: 
                                </label>

                                <select class="form-control" style="margin-left:10px" id="select_office">
                                    <option> Please Select </option>
                                    @if(auth()->user()->is_admin)
                                    <option value="MAIN OFFICE"> Main Office </option>
                                    @endif
                                    
                                    @foreach ($offices as $office)
                                    <option value="{{$office->name}}">{{ $office->name }}</option>
                                    @endforeach
                                </select>
                              </div>
                        </div> --}}
                        {{-- <div class="clearfix"></div>
                        <div class="form-inline float-right">
                            <div class="form-check mb-2 mr-sm-2">
                                <button class="btn btn-primary ">Print All</button>
                              </div>
                        </div>
                         --}}
                        <?php 
                            $ctr = 1;
                        ?>
                        <table class="table" id="table-list">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Batch ID</td>
                                    <td>Pulled At</td>
                                    {{-- <td>Exported At</td> --}}
                                    {{-- <td>Action</td> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($batches as $batch)
                                        <tr>
                                            <td>{{$ctr}}</td>
                                            <td><a href="{{route('forms.by.batch',$batch->batch_id)}}">{{$batch->batch_id}}</a></td>
                                            <td>{{$batch->pulledAt()->diffForHumans()}}</td>
                                      
                                        </tr>
                                @endforeach
                                <?php $ctr++; ?>
                                
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
                @if(request()->has('branch'))
                $('#pills-profile-tab').click()
                $('#select_office').val('{{request()->branch}}')
                @endif
                @if(request()->has('page'))
                $('#pills-profile-tab').click()
                @endif
                $('#select_office').change(function(){
                    var selected = $(this).val()
                    var href = window.location.origin + window.location.pathname
                    window.location = href+'?branch='+selected
                })

            })(jQuery);
        });
    </script>
@endsection