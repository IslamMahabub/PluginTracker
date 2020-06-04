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
                            <a href="{{ route('settings.index') }}"><button type="button" class="btn btn-info">Back</button></a>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row m-t-30">
                <div class="col-md-12">
                    {!! Form::model($settings, ['method' => 'PATCH','route' => ['settings.update', $settings->id]]) !!}
                        <div class="card">
                            <div class="card-header">
                                <strong>Settings</strong> Form
                            </div>
                            <div class="card-body card-block">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="select" class=" form-control-label">Plugin</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <select name="plugins" id="select" class="form-control" value="{{ old('plugins') }}" >
                                            <option value="">Select Plugin</option>
                                            @foreach($plugins as $pluginsResult)
                                                <option value="{{ $pluginsResult->plugin }}" @if($settings->plugin_name == $pluginsResult->plugin) selected @endif>{{ $pluginsResult->plugin }}</option>
                                            @endforeach
                                        </select>
                                        @error('plugins')
                                        <span class="help-block text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="textarea-input" class=" form-control-label">Slug</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="slag" name="slag" placeholder="Wordpress Slug" value="{{ $settings->plugin_slag }}" class="form-control" autocomplete="off">
                                        @error('slag')
                                        <span class="help-block text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-lg btn-primary btn-block">
                                <i class="fa fa-dot-circle-o"></i> Submit
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
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