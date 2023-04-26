@extends('layout')
@section('page_subheader')
    @include('components.subheader', ['title' => 'Đơn hàng'])
@stop
@section('content')
    <p>
        This view is loaded from module: {!! config('admin.name') !!}
    </p>
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Import Excel
                            </h3>
                        </div>
                    </div>
                </div>
                <form action="{{route('admin.order-reason-cancel.submit-import-excel')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group m-form__group row">
                        <div class="col-lg-3 {{ $errors->has('file') ? ' has-danger' : '' }}">

                            <input type="file" name="file">
                            @if ($errors->has('file'))
                                <span class="form-control-feedback"> {{ $errors->first('file') }}</span>
                            @endif
                        </div>
                        <div class="m-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <input type="submit" class="btn m-btn--pill m-btn--air btn-success" value="Import">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@stop
