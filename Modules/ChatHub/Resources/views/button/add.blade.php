@extends('backpack::layout')
@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <!-- Default box -->
            <a href="{{route('button')}}"><i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">Button</span></a><br><br>

            <form method="POST" action="{{route('button.create')}}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('Add button')</h3>
                    </div>
                    @if (count($errors) > 0)
                        <div class="callout callout-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                    <div class="box-body row">
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->

                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        <!-- text input -->
                        <div class="form-group col-sm-12">
                            <label>@lang('Title')</label>
                            <input type="text" name="title" value="{{old('title')}}" class="form-control">
                        </div>
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        <!-- text input -->
                        <div class="form-group col-sm-12">
                            <label>@lang('Type')</label>
                            <select name="type" onchange="MyDisabled()" class="form-control">
                                <option value="postback" @if(old('type')=='postback')selected @endif>@lang('Post back')</option>
                                <option value="web_url" @if(old('type')=='web_url')selected @endif>@lang('Web Url')</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12">
                            <label>@lang('Url')</label>
                            <input type="text" name="url" id="url" value="{{old('url')}}" class="form-control" @if(!old('url')) disabled @endif>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>@lang('Payload')</label>
                            <input type="text" name="payload" id="payload" value="{{old('payload')}}" class="form-control">
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-save"></i> @lang('Save')</span></button>
                        <a href="{{route('button')}}" class="btn btn-default ladda-button" data-style="zoom-in"><span class="ladda-label">@lang('Cancel')</span></a>
                    </div><!-- /.box-footer-->
                </div></form><!-- /.box -->
        </div>
    </div>
@endsection
@section('after_styles')
    <!-- include select2 css-->
    <link href="{{ asset('vendor/backpack/select2/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/backpack/select2/select2-bootstrap-dick.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('after_scripts')
    <script src="{{ asset('vendor/backpack/select2/select2.js') }}"></script>
    <script>
        function MyDisabled(){
            if($('#url').attr('disabled')){
                $('#payload').val('');
                $("#url").attr("disabled", false);
                $("#payload").attr("disabled", true);
            }else{
                $('#url').val('');
                $("#url").attr("disabled", true);
                $("#payload").attr("disabled", false);
            }
            
        }
    </script>
@endsection