<style>
    div.widget-style{
        -webkit-box-flex: 0;
        -ms-flex: 0 0 33.33333333%;
        flex: 0 0 33.33333333%;
        max-width: 100%;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 5px;
        box-sizing: border-box;
        margin-bottom: 10px;
        text-align: center;
    }
    div>span>img{
        display: block;
        cursor: move;
        padding: 12px 10px;
        font-size: 12px;
        color: #53595f;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 400ms;
        -webkit-transition: all 400ms;
        background: #fff;
        border-radius: 3px;
        border: 1px solid #e8e8e8;
    }
    div>span>span.widget-span{
        display: block;
        cursor: move;
        padding: 12px 10px;
        font-size: 12px;
        color: #53595f;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 400ms;
        -webkit-transition: all 400ms;
        background: #fff;
        border-radius: 3px;
        border: 1px solid #e8e8e8;
    }
    div>span>span>span{
        display: block;
        overflow: hidden;
    }
    div div>span>span>i {
        margin-bottom: 3px;
        color: #53595f;
        font-size: 18px;
        transition: 400ms;
        display: block;
    }
    div div>span>span>span {
        transition: all 400ms;
        -webkit-transition: all 400ms;
        position: relative;
        top: 1px;
    }
    h5 {
        text-align: center;
        padding: 0;
        margin: 15px 10px 20px;
        font-weight: 400;
        color: #969ca2;
        position: relative;
        font-size: 14px;
    }
    div.widget-padding{
        padding: 3px 3px;
    }
    div.widget-drag{
        opacity: 0.4;
    }
    div.parent-col-style{
        border-radius: 3px;
        border: 1px solid #e8e8e8;
        margin: 1px -1px;
    }
    div.on-hover-img {
        display: none;
    }
    div>span:hover > div.on-hover-img{
        display: inline-block;
    }
</style>
<div class="col-lg-4 parent-col-style">
    <div class="row widget-style">
        <input type="hidden" id="dashboard_id" name="dashboard_id" value="{{$dashboard_id}}">
        <div class="col-lg-12 form-group pt-2">
            <input type="text" class="form-control" name="search-widget" id="search-widget"
                   placeholder="@lang("Nhập tên widget...")">
        </div>
        <div class="col-lg-12 form-group pt-2">
            <select id="search-widget-type" class="form-control widget-select2">
                <option value="" selected>Chọn widget</option>
                <option value="mini_column">Mini column</option>
                <option value="column">Column</option>
                <option value="tab">Tab</option>
            </select>
        </div>
    </div>
    <div class="row widget-style" id="list-widget" style="overflow-y: scroll;max-height: 600px;">
        @foreach($lstWidget as $key => $value)
            <div draggable="true" ondragstart="dragStart(event);" id="{{$value['widget_code']}}"
                 data-widget-type="{{$value['widget_type']}}"
                 class="widget-padding col-lg-{{$value['size_column']}}"
                 data-col="{{$value['size_column']}}" data-id="{{$value['dashboard_widget_id']}}">
                <input type="hidden" class="value-image" value="{{asset('static/backend/images/dashboard') .'/' . $value['image']}}">
                 <span title="{{$value['widget_name'] . '('. $value['widget_type'] .')'}}">
                    <span draggable="true" class="widget-span">
                        {!! $value['icon'] !!}
                        <span>
                            {{$value['widget_name']}}
                        </span>
                    </span>
                </span>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-lg-3">
        </div>
        <div class="col-lg-6">
            <button onclick="addComponent()"
               class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill">
                <span>
                    <i class="fa fa-plus-circle"></i>
                    <span> {{__('THÊM COMPONENT')}}</span>
                </span>
            </button>
        </div>
        <div class="col-lg-3">
        </div>
    </div>
</div>
<div class="col-lg-8 parent-col-style" style="overflow: scroll;height: 500px;">
    <h5><span>@lang('Dashboard Custom Displaying')</span></h5>
    <div id="dashboard-sortable" class="dashboard-sortable">
        @foreach($lstComponentDefault as $key => $value)
            <div data-component-type="{{$value['component_type']}}" ondragover="dragOver(event);" ondrop="drop(this,event);"  class="ui-state-default ui-sortable-handle dashboard-sortable ui-sortable row widget-style" style="min-height: 100px;margin-left: 0.2rem">
                <div class="col-lg-12 unsortable">
                    <span class="float-right">
                        <i class="la la-remove" draggable="false"  onclick="removeComponent(this)"></i>
                    </span>
                </div>
                @foreach($value['widget'] as $k => $v)
                <div class="col-lg-{{$v['size_column']}}">
                    <input type="hidden" name="dashboard_widget_id" value="{{$v['dashboard_widget_id']}}">
                    <input type="hidden" name="component-widget" value="{{$v['widget_code']}}">
                    <div class="ui-state-default ui-sortable-handle widget-padding" data-col="{{$v['size_column']}}">
                        <span title="{{$v['widget_name']}}">
                            <label class="float-left">
                                {{$v['widget_display_name']}}
                            </label>
                            <div class="on-hover-img float-right">
                                <span>
                                    <i class="la la-edit" draggable="true" onclick="editWidget(this, '{{$v['widget_code']}}','{{$v['widget_display_name']}}')"></i>
                                    <i class="la la-remove" draggable="true"  onclick="removeWidget(this, '{{$v['widget_code']}}')"></i>
                                </span>
                            </div>
                            <img class="m--bg-metal m-image img-sd " src="{{asset('static/backend/images/dashboard') .'/' . $v['image']}}"
                                 alt="Hình ảnh" width="100%" height="{{$v['size_column']/3*100}}px !important">
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<div class="m-form__actions m--align-right col-lg-12 float-right">
    <a href="{{route('dashbroad.dashboard-config')}}"
       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
    </a>
    <button type="button" onclick="addDashboard(event)"
            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
    </button>
</div>
<script type="text/template" id="tpl-widget-component">
    <input type="hidden" name="dashboard_widget_id" value="{dashboard_widget_id}">
    <input type="hidden" name="component-widget" value="{widget_code}">
    <div class="ui-state-default ui-sortable-handle widget-padding" data-col="{size_column}">
        <span title="{widget_name}">
            <label class="float-left">
                {widget_display_name}
            </label>
            <div class="on-hover-img float-right">
                <span>
                    <i class="la la-edit" draggable="true" onclick="editWidget(this, '{widget_code}','{widget_display_name}')"></i>
                    <i class="la la-remove" draggable="true"  onclick="removeWidget(this, '{widget_code}')"></i>
                </span>
            </div>
            <img class="m--bg-metal m-image img-sd " src="{image}"
                 alt="Hình ảnh" width="100%" height="{size_column_height}px !important">
        </span>
    </div>
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#list-widget').children('div').each(function(){
                var id = $(this).attr('id');
                if($('#dashboard-sortable').html().includes(`"${id}"`)){
                    $(this).addClass('widget-drag')
                }

            });
            $(".dashboard-sortable").sortable({
                cancel: ".unsortable"
            });
            $(".dashboard-sortable").disableSelection();
            $(".widget-select2").select2();
        });
    });
    function addDashboard(e) {
        e.preventDefault();
        var arrComponent = [];
        var flag = 0;
        $('#dashboard-sortable').children('div').each(function(key,value){
            var arrWidget = [];
            let i = 1;
            $(this).children('div').each(function(k,v){
                if($($(this).find('input[name="dashboard_widget_id"]')).val()){
                    arrWidget.push({
                        'dashboard_widget_id': $($(this).find('input[name="dashboard_widget_id"]')).val(),
                        'widget_display_name': $($(this).find('div>span>label')).text().trim(),
                        'widget_position': i++
                    });
                }
            });
            if(arrWidget.length == 0){
                swal.fire('Chưa thêm widget vào component', '', "error");
                flag = 1;
            }
            arrComponent.push({
                'component_type': $(this).attr('data-component-type'),
                'component_position': key+1,
                'arrWidget': arrWidget
            });
        });
        if(arrComponent.length == 0){
            swal.fire('Chưa thêm component', '', "error");
            return;
        }
        if(flag == 1){
            return;
        }
        $.ajax({
            url: laroute.route('dashbroad.dashboard-config.create-dashboard'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                'dashboard_id' : $('#dashboard_id').val(),
                'arrComponent' : arrComponent
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    window.location.href = '{{route('dashbroad.dashboard-config')}}';
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    }
    function dragStart(e) {
        /*Thiết lập tính năng cho phép di chuyển đối tượng*/
        e.dataTransfer.effectAllowed = "move";
        /* thiết lập giá trị và loại đối tượng cho dữ liệu được drag and drop*/
        e.dataTransfer.setData("Text", e.target.offsetParent.id);
    }
    function dragOver(e) {
        /*Ngăn chặn việc xử lý mặc định của trình duyệt*/
        e.preventDefault();
        e.stopPropagation();
    }
    function drop(a, e) {
        /*hủy sự kiện cho các hành động khác*/
        e.stopPropagation();
        e.preventDefault();
        var currCol = 0;
        $(a).children('div').children('div').each(function(key,value){
            currCol += parseInt($(value).attr('data-col'));
        });
        /*Truy xuất kéo dữ liệu theo loại*/
        var data = e.dataTransfer.getData("Text");

        // check tồn tại widget
        if(!$('#dashboard-sortable').html().includes(`"${data}"`)){
            if($(a).attr('data-component-type') == $(`#${data}`).attr('data-widget-type')) {
                // check ít hoặc đủ số column mới cho thêm
                if (currCol + parseInt($(`#${data}`).attr('data-col')) <= 12) {
                    var widget_name = $($(`#${data}`).children('span').children('span').children('span')).text().trim();
                    swal.fire({
                        title: 'Thuộc tính của ' + widget_name,
                        type: 'question',
                        showCancelButton: true,
                        cancelButtonText: "Hủy",
                        confirmButtonText: "Áp dụng vào bố cục",
                        html:
                            '<input id="pop_widget_name" class="swal2-input" value="' + widget_name + '"><br>' + widget_name,
                        preConfirm: () => {
                            if ($('#pop_widget_name').val() == '') {
                                swal.showValidationError("Tên widget không được trống");
                            } else if ($('#pop_widget_name').val().length > 255) {
                                swal.showValidationError("Tên widget không vượt quá 255 ký tự");
                            }
                        }
                    }).then(function (result) {
                        if (result.value) {
                            $(`#${data}`).css("opacity", "0.4");
                            var tpl = $('#tpl-widget-component').html();
                            tpl = tpl.replace(/{widget_code}/g, data);
                            tpl = tpl.replace(/{size_column}/g, $(`#${data}`).attr('data-col'));
                            tpl = tpl.replace(/{dashboard_widget_id}/g, $(`#${data}`).attr('data-id'));
                            tpl = tpl.replace(/{size_column_height}/g, parseInt($(`#${data}`).attr('data-col')) / 3 * 100);
                            tpl = tpl.replace(/{image}/g, $(`#${data}`).children('input.value-image').val());
                            tpl = tpl.replace(/{widget_name}/g, widget_name);
                            tpl = tpl.replace(/{widget_display_name}/g, $('#pop_widget_name').val());
                            // widget_name
                            // widget_display_name
                            var html = document.createElement('div');
                            html.className = `col-md-${$(`#${data}`).attr('data-col')}`;
                            html.innerHTML = tpl;
                            /*Thêm hình ảnh được kéo vào ô chúng ta đã tạo từ trước*/
                            $(a).append(html);
                            // e.target.insertBefore(html, e.target.class);
                            $(".dashboard-sortable").sortable({
                                cancel: ".unsortable"
                            });
                            $(".dashboard-sortable").disableSelection();
                            // e.target.appendChild(document.getElementById(data));
                        }
                    });
                } else {

                }
            }
        }
    }
    function removeWidget(e, code){
        $(`#${code}`).css("opacity", "1");
        $(e).closest('div').parent('span').parent('div').parent('div').remove();
    }
    function editWidget(e, code, widget_name){
        swal.fire({
            title: 'Thuộc tính của '+ widget_name,
            type: 'question',
            showCancelButton: true,
            cancelButtonText: "Hủy",
            confirmButtonText: "Áp dụng vào bố cục",
            html:
                '<input id="pop_widget_name" class="swal2-input" value="'+widget_name+'"><br>'+widget_name,
            preConfirm: () => {
                if($('#pop_widget_name').val() == ''){
                    swal.showValidationError("Tên widget không được trống");
                }else if($('#pop_widget_name').val().length > 255){
                    swal.showValidationError("Tên widget không vượt quá 255 ký tự");
                }
            }
        }).then(function (result) {
            if (result.value) {
                $('input[value="'+code+'"]').next().children('span').children('label').text($('#pop_widget_name').val());
            }
        });
    }
    function removeComponent(e){
        $(e).closest('div').parent('div').find('input[type="hidden"]').each(function(value, key){
            $(`#${$(key).val()}`).css("opacity", "1");
        });
        $(e).closest('div').parent('div').remove();
    }
    function addComponent(){
        swal.fire({
            title: 'Chọn loại Component',
            type: 'question',
            showCancelButton: true,
            cancelButtonText: "Hủy",
            confirmButtonText: "Áp dụng",
            html:
                '<select id="pop_component_type" class="swal2-input">' +
                '<option value="mini_column" selected>Mini column</option>' +
                '<option value="column" selected>Column</option>' +
                '<option value="tab">Tab</option></select>',
        }).then(function (result) {
            if (result.value) {
                $('#dashboard-sortable').append(`
                <div data-component-type="${$('#pop_component_type option:selected').val()}" ondragover="dragOver(event);" ondrop="drop(this,event);" class="ui-state-default ui-sortable-handle dashboard-sortable ui-sortable row widget-style" style="min-height: 100px;margin-left: 0.2rem">
                    <div class="col-lg-12 unsortable">
                        <span class="float-right">
                            <i class="la la-remove" draggable="true"  onclick="removeComponent(this)"></i>
                        </span>
                    </div>
                </div>
                `)
            }
        });
    }
    $('#search-widget').keypress(function (e) {
        if (e.which == 13) {
            $.ajax({
                url: laroute.route('dashbroad.dashboard-config.list-widget'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'search' : $('#search-widget').val(),
                    'widget_type' : $('#search-widget-type option:selected').val(),
                },
                success: function (res) {
                    $('#list-widget').html('');
                    $.map(res.lstWidget, function (a) {
                        $('#list-widget').append(`
                            <div draggable="true" ondragstart="dragStart(event);" id="${a.widget_code}"
                                 data-widget-type="${a.widget_type}"
                                 class="widget-padding col-lg-${a.size_column}"
                                 data-col="${a.size_column}" data-id="${a.dashboard_widget_id}">
                                <input type="hidden" class="value-image" value="{{asset('static/backend/images/dashboard') .'/'}}${a.image}">
                                <span title="${a.widget_name} `+ '(' +` ${a.widget_type} `+ ')'+`">
                                    <span draggable="true" class="widget-span">
                                        ${a.icon}
                                        <span>
                                            ${a.widget_name}
                                        </span>
                                    </span>
                                </span>
                            </div>
                            `);
                    });

                    $('#list-widget').children('div').each(function(){
                        var id = $(this).attr('id');
                        if($('#dashboard-sortable').html().includes(`"${id}"`)){
                            $(this).addClass('widget-drag')
                        }

                    });
                }
            });
        }
    });

    $('#search-widget-type').on('change', function(e){
        e.preventDefault();
        $.ajax({
            url: laroute.route('dashbroad.dashboard-config.list-widget'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                'search' : $('#search-widget').val(),
                'widget_type' : $('#search-widget-type option:selected').val(),
            },
            success: function (res) {
                $('#list-widget').html('');
                $.map(res.lstWidget, function (a) {
                    $('#list-widget').append(`
                            <div draggable="true" ondragstart="dragStart(event);" id="${a.widget_code}"
                                 data-widget-type="${a.widget_type}"
                                 class="widget-padding col-lg-${a.size_column}"
                                 data-col="${a.size_column}" data-id="${a.dashboard_widget_id}">
                                <input type="hidden" class="value-image" value="{{asset('static/backend/images/dashboard') .'/'}}${a.image}">
                                <span title="${a.widget_name} `+ '(' +` ${a.widget_type} `+ ')'+`">
                                    <span draggable="true" class="widget-span">
                                        ${a.icon}
                                        <span>
                                            ${a.widget_name}
                                        </span>
                                    </span>
                                </span>
                            </div>
                            `);
                });

                $('#list-widget').children('div').each(function(){
                    var id = $(this).attr('id');
                    if($('#dashboard-sortable').html().includes(`"${id}"`)){
                        $(this).addClass('widget-drag')
                    }

                });
            }
        });
    })
</script>