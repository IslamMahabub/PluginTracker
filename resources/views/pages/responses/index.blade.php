@extends('layouts.app')
@section('title') Response @endsection

@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Auto Response Message</h2>
                        <h2 class="title-1">
                            <a href="{{ route('responses.create') }}"><button type="button" class="btn btn-info">Add New</button></a>
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


                            {{--<div class="card">
                                <div class="card-header" role="tab" id="questionTwo">
                                    <h5 class="card-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#faq" href="#answerTwo" aria-expanded="false" aria-controls="answerTwo">
                                            Can I wear my boots inside?
                                        </a>
                                    </h5>
                                </div>
                                <div id="answerTwo" class="collapse" role="tabcard" aria-labelledby="questionTwo">
                                    <div class="card-body">
                                        No. Your mama should've told you about this.
                                    </div>
                                </div>
                            </div>--}}


                        <table class="table table-borderless table-data3" id="responseTable" style="text-align: left">
                            <thead>
                            <tr>
                                <th style="width: 20%">Reason</th>
                                <th style="width: 20%; text-align: center">Message Subject</th>
                                <th style="width: 40%; text-align: center">Response Message</th>
                                <th style="width: 05%; text-align: center">Status</th>
                                <th style="width: 15%; text-align: center ">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($responses as $result)
                                <tr>
                                    <td class="font-weight-bold">{{ ucfirst(str_replace('-', ' ', $result->reason)) }}</td>
                                    <td>
                                        {{ $result->subject }}
                                    </td>
                                    <td>
                                        {{ $result->message }}
                                    </td>
                                    <td class="text-right">
                                        {{ ($result->status) ? 'Enable' : 'Disable' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('responses.edit',$result->id) }}">
                                        <button type="button" class="btn btn-success btn-sm">Edit</button>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE','route' => ['responses.destroy', $result->id],'style'=>'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
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
