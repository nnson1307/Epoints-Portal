@extends('layout')
@section('page_subheader')
    @include('components.subheader', ['title' => '{{__('Chi nhánh')}}'])
@stop

@section('content')


    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Thêm chi nhánh
                            </h3>
                        </div>
                    </div>
                </div>
                {!! Form::model($item,['method'=>'post','route'=>['admin.store.edit', $item->store_id, 'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']]) !!}
                {!! csrf_field() !!}
                <div class="m-portlet__body">
                <div class="m-portlet__body">
                    @if (session('messages'))
                        <div class="alert alert-warning alert-dismissible">
                            <strong>Warning!</strong> {!! session('messages') !!}.
                        </div>
                    @endif
                    <div class="form-group m-form__group row">



                        <div class="col-lg-6  ">
                            <label class="col-lg-12 col-form-label">
                                {{__('Tên chi nhánh')}} :
                            </label>
                            <div class="{{ $errors->has('store_name') ? ' has-danger' : '' }}">
                            {!! Form::text('store_name',null,['class' => 'form-control m-input','placeholder' => __('Tên chi nhánh')]) !!}
                            <br>
                            @if ($errors->has('store_name'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('store_name') }}
                                </span>
                                <br>
                            @endif
                            </div>
                            <span class="m-form__help">
									Vui lòng nhập tên chi nhánh
                            </span>
                        </div>



                        <div class="col-sm-6">
                            <label class="col-lg-12 col-form-label">
                                Tỉnh thành :
                            </label>
                            <div class="form-group {{ $errors->has('province_id') ? ' has-danger' : '' }}">
                                <select name="province_id" id="province" class="form-control s-province">
                                    <option value="">Tỉnh/ Thành</option>

                                    @foreach($optionProvince as $key=>$City)
                                        @if($item->province_id == $key)
                                            <option value="{{$key}}" selected>{{$City}}</option>
                                        @else
                                        <option value="{{$key}}" >{{$City}}</option>
                                        @endif
                                    @endforeach

                                </select>
                                <br>
                                @if ($errors->has('province_id'))
                                    <span class="form-control-feedback">
                                             {{ $errors->first('province_id') }}
                                        </span>
                                    <br>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">

                        <div class="col-lg-6 {{ $errors->has('address') ? ' has-danger' : '' }} ">
                            <label class="col-lg-12 col-form-label">
                                {{__('Địa chỉ')}} :
                            </label>
                            {!! Form::text('address',null,['class' => 'form-control m-input','placeholder' => 'Nhập số và tên đường']) !!}
                            <br>
                            @if ($errors->has('address'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('address') }}
                                </span>
                                <br>
                            @endif
                            <span class="m-form__help">
									Vui lòng nhập đúng số và tên đường có dấu
                            </span>
                        </div>



                        <div class="col-sm-6">
                            <label class="col-lg-12 col-form-label">
                                Quận huyện :
                            </label>
                            <div class="form-group {{ $errors->has('district_id') ? ' has-danger' : '' }}">
                                <select name="district_id" id="district" class="form-control s-province">
                                    <option value="">Quận/  Huyện</option>
                                    @foreach($optionDistrict as $key=>$district)
                                        @if($item->district_id == $key)
                                            <option value="{{$key}}" selected>{{$district}}</option>
                                        @else
                                            <option value="{{$key}}" >{{$district}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <br>
                                @if ($errors->has('district_id'))
                                    <span class="form-control-feedback">
                                             {{ $errors->first('district_id') }}
                                        </span>
                                    <br>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6">
                            <label class="col-lg-12 col-form-label">
                                {{__('Hình ảnh')}} :
                            </label>

                                <div class="m-dropzone dropzone dz-clickable" action="inc/api/dropzone/upload.php" id="dropzoneone" >
                                    <div class="m-dropzone__msg dz-message needsclick">
                                        <h3 href="{{asset($item->store_image)}}" class="m-dropzone__msg-title">
                                            Drop files here or click to upload.
                                        </h3>
                                        <span class="m-dropzone__msg-desc">This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.</span>
                                        <input type="hidden" id="file_image" name="store_image" value="file_name">
                                    </div>

                                </div>

                        </div>



                        <div class="col-sm-6">
                            <label class="col-lg-12 col-form-label">
                                Phường xã :
                            </label>
                            <div class="form-group {{ $errors->has('ward_id') ? ' has-danger' : '' }}">
                                <select name="ward_id" id="ward" class="form-control s-province">
                                    <option value="">Phường/ Xã</option>
                                    @foreach($optionWard as $key=>$ward)
                                        @if($item->ward_id == $key)
                                            <option value="{{$key}}" selected>{{$ward}}</option>
                                        @else
                                            <option value="{{$key}}" >{{$ward}}</option>
                                        @endif
                                    @endforeach

                                </select>
                                <br>
                                @if ($errors->has('ward_id'))
                                    <span class="form-control-feedback">
                                             {{ $errors->first('ward_id') }}
                                        </span>
                                    <br>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-sm-6">
                            <label class="col-lg-12 col-form-label">
                                Bản đồ :
                            </label>
                        </div>

                    </div>

                    {{--button lưu--}}
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <button type="submit" class="btn btn-success">Lưu lại</button>
                                    <a href="{{ route('admin.store') }}" class="btn btn-secondary">Hủy</a>
                                    <input type="reset" value="Xóa" class="btn btn-danger pull-right">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>

@stop

@section('after_script')
    <script src="{{asset('static/backend/js/admin/store/change.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/store/dropzone.js')}}" type="text/javascript"></script>

@stop
