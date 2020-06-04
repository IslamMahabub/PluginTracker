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
                            <a href="{{ route('responses.index') }}"><button type="button" class="btn btn-info">Back</button></a>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row m-t-30">
                <div class="col-md-12">
                    {!! Form::model($response, ['method' => 'PATCH','route' => ['responses.update', $response->id]]) !!}
                    <div class="card">
                        <div class="card-header">
                            <strong>Auto Response</strong> Form
                        </div>
                        <div class="card-body card-block">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="select" class=" form-control-label">Reason</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="reason" id="select" class="form-control" value="{{ old('reason') }}" >
                                        <option value="">Select Reason</option>
                                        @foreach($reasons as $reasonResult)
                                            <option value="{{ $reasonResult->reason_id }}" @if($response->reason == $reasonResult->reason_id) selected @endif>{{ ucfirst(str_replace('-', ' ', $reasonResult->reason_id)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('reason')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="textarea-input" class=" form-control-label">Message Subject</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="subject" name="subject" placeholder="Subject" value="{{ $response->subject }}" class="form-control">
                                    @error('subject')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="textarea-input" class=" form-control-label">Response Message</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <textarea name="message" id="textarea-input" rows="9" placeholder="Message" class="form-control">{{ $response->message }}</textarea>
                                    @error('message')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="textarea-input" class=" form-control-label">Sending Status</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <div class="form-check">
                                        <div class="checkbox">
                                            <label for="checkbox1" class="form-check-label ">
                                                <input type="hidden" id="status" name="status" value="0" class="form-control">
                                                <input type="checkbox" id="status" name="status" {{ ($response->status == 1) ? 'checked' : '' }} value="1" class="form-check-input">Enable
                                            </label>
                                        </div>
                                    </div>
                                    @error('status')
                                    <span class="help-block text-danger">{{ $status }}</span>
                                    @enderror
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