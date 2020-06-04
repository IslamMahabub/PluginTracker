@extends('layouts.app')
@section('title') Response @endsection

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Change password</h2>
                        </div>
                    </div>
                </div>
                <div class="row m-t-30">
                    <div class="col-md-12">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form class="form-horizontal" method="POST" action="{{ route('changePassword') }}">
                        {{ csrf_field() }}
                        <div class="card">
                            <div class="card-header">
                                <strong>Change password</strong> Form
                            </div>
                            <div class="card-body card-block">
                                <div class="row form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                    <label for="new-password" class="col-md-4 control-label">Current Password</label>

                                    <div class="col-md-6">
                                        <input id="current-password" type="password" class="form-control" name="current-password" required>

                                        @if ($errors->has('current-password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                                    <label for="new-password" class="col-md-4 control-label">New Password</label>

                                    <div class="col-md-6">
                                        <input id="new-password" type="password" class="form-control" name="new-password" required>

                                        @if ($errors->has('new-password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label for="new-password-confirm" class="col-md-4 control-label">Confirm New Password</label>

                                    <div class="col-md-6">
                                        <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">
                                    <i class="fa fa-rotate-right"></i> Change Password
                                </button>
                            </div>
                        </div>
                        </form>
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