@extends('layouts.app')
@section('title') Settings @endsection

@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Settings</h2>
                        <h2 class="title-1">
                            <a href="{{ route('settings.create') }}"><button type="button" class="btn btn-info">Add New</button></a>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row m-t-30">
                <div class="col-md-12">
                    <!-- DATA TABLE-->
                    <div class="table-responsive m-b-40">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif


                        <table class="table table-borderless table-data3" id="responseTable" style="text-align: left">
                            <thead>
                            <tr>
                                <th style="width: 20%">SL</th>
                                <th style="width: 20%; text-align: center">Plugin</th>
                                <th style="width: 40%; text-align: center">Wordpress Slug</th>
                                <th style="width: 15%; text-align: center ">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($key = 1)
                            @foreach($settings as $result)
                                <tr>
                                    <td class="font-weight-bold">{{ $key }}</td>
                                    <td>
                                        {{ $result->plugin_name }}
                                    </td>
                                    <td>
                                        {{ $result->plugin_slag }}
                                    </td>
                                    <td>
                                        <a href="{{ route('settings.edit',$result->id) }}">
                                        <button type="button" class="btn btn-success btn-sm">Edit</button>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE','route' => ['settings.destroy', $result->id],'style'=>'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @php($key++)
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- END DATA TABLE-->
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © {{ date('Y') }} WebAppick. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready( function () {
        $('#responseTable').DataTable( {
            "order": []
        } );
    } );
</script>
@endsection
