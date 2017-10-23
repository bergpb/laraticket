@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>{{ trans('module.priority.edit_title') }}</h1>
        </div>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}">{{ trans('miscellaneous.dashboard') }}</a></li>
            <li>{{ trans_choice('miscellaneous.auxiliary', 2) }}</li>
            <li><a href="{{ route('priority.index') }}">{{ trans('module.priority.title') }}</a></li>
            <li class="active">{{ trans('module.priority.edit_title') }}</li>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('module.priority.edit_subtitle') }}</h3>
                    </div>
                    <form action="{{ route('priority.update', $data->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="panel-body">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="inputName">{{ trans('form.title') }}{!! trans('form.required_field') !!}</label>
                                <input type="text" name="title" class="form-control" id="inputName" value="{{ $data->title }}">
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="textareaDescription">{{ trans('form.description') }}</label>
                                <textarea name="description" class="form-control" id="textareaDescription" rows="5">{{ $data->description }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-default">{{ trans('form.update') }}</button>
                            <a href="{{ route('priority.index') }}" class="btn btn-link">{{ trans('form.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
