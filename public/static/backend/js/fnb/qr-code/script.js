$('#autotable').PioTable({
    baseUrl: laroute.route('fnb.qr-code.list')
});

let imageUtil = {};

function svg2img(){
    var svg = document.querySelector('svg');
    var xml = new XMLSerializer().serializeToString(svg);
    var svg64 = btoa(xml); //for utf8: btoa(unescape(encodeURIComponent(xml)))
    var b64start = 'data:image/svg+xml;base64,';
    var image64 = b64start + svg64;
    return image64;
}

let area = 0;
let table = 0;
var qrCode = {
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),
    _init : function (){
        $('select').select2();

        var arrRange = {};
        arrRange[qrCode.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[qrCode.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[qrCode.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[qrCode.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[qrCode.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[qrCode.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".daterange_picker").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // maxDate: moment().endOf("day"),
            // startDate:moment().subtract(6, "days"),
            // endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": qrCode.jsonLang["Đồng ý"],
                "cancelLabel": qrCode.jsonLang["Thoát"],
                "customRangeLabel": qrCode.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    qrCode.jsonLang["CN"],
                    qrCode.jsonLang["T2"],
                    qrCode.jsonLang["T3"],
                    qrCode.jsonLang["T4"],
                    qrCode.jsonLang["T5"],
                    qrCode.jsonLang["T6"],
                    qrCode.jsonLang["T7"]
                ],
                "monthNames": [
                    qrCode.jsonLang["Tháng 1 năm"],
                    qrCode.jsonLang["Tháng 2 năm"],
                    qrCode.jsonLang["Tháng 3 năm"],
                    qrCode.jsonLang["Tháng 4 năm"],
                    qrCode.jsonLang["Tháng 5 năm"],
                    qrCode.jsonLang["Tháng 6 năm"],
                    qrCode.jsonLang["Tháng 7 năm"],
                    qrCode.jsonLang["Tháng 8 năm"],
                    qrCode.jsonLang["Tháng 9 năm"],
                    qrCode.jsonLang["Tháng 10 năm"],
                    qrCode.jsonLang["Tháng 11 năm"],
                    qrCode.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (ev) {
            configColumn.searchTable();
        });

        $(".daterange_picker").val('');

        $('.date_picker').datepicker({
            language: 'vi',
            orientation: "bottom left", todayHighlight: !0,
            format: 'dd/mm/yyyy',
        });

    },

    _initAdd : function (){
        $('select').select2();

        $(".datetimepicker").datetimepicker({
            todayHighlight: !0,
            autoclose: !0,
            // pickerPosition: "bottom-left",
            format: "hh:ii dd/mm/yyyy",
            // minDate: new Date(),
            // locale: 'vi'
        });

        var arrRange = {};
        arrRange[qrCode.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[qrCode.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[qrCode.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[qrCode.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[qrCode.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[qrCode.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".daterange_picker_search").daterangepicker({
            autoApply: true,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": qrCode.jsonLang["Đồng ý"],
                "cancelLabel": qrCode.jsonLang["Thoát"],
                "customRangeLabel": qrCode.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    qrCode.jsonLang["CN"],
                    qrCode.jsonLang["T2"],
                    qrCode.jsonLang["T3"],
                    qrCode.jsonLang["T4"],
                    qrCode.jsonLang["T5"],
                    qrCode.jsonLang["T6"],
                    qrCode.jsonLang["T7"]
                ],
                "monthNames": [
                    qrCode.jsonLang["Tháng 1 năm"],
                    qrCode.jsonLang["Tháng 2 năm"],
                    qrCode.jsonLang["Tháng 3 năm"],
                    qrCode.jsonLang["Tháng 4 năm"],
                    qrCode.jsonLang["Tháng 5 năm"],
                    qrCode.jsonLang["Tháng 6 năm"],
                    qrCode.jsonLang["Tháng 7 năm"],
                    qrCode.jsonLang["Tháng 8 năm"],
                    qrCode.jsonLang["Tháng 9 năm"],
                    qrCode.jsonLang["Tháng 10 năm"],
                    qrCode.jsonLang["Tháng 11 năm"],
                    qrCode.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (ev) {

        });

        $(".daterange_picker_search").val('');

        $('#apply_branch_id').select2({
            dropdownParent: $('#apply_branch_id').parent(),
            width: '100%',
            placeholder: qrCode.jsonLang['Chọn chi nhánh'],
            ajax: {
                url: laroute.route('fnb.qr-code.list-branch'),
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                dataType: 'json',
                method: 'POST',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.branch_id,
                                text: item.branch_name,
                            };
                        }),
                        pagination: {
                            more: data.current_page + 1
                        }
                    };
                },
            }
        }).on('select2:select', function (e) {
            $('#apply_arear_id').val('').trigger('change');
            $('#apply_table_id').val('').trigger('change');
        });

        $('#apply_arear_id').select2({
            dropdownParent: $('#apply_arear_id').parent(),
            width: '100%',
            placeholder: qrCode.jsonLang['Chọn khu vực'],
            ajax: {
                url: laroute.route('fnb.qr-code.list-area'),
                data: function (params) {
                    return {
                        branch_id : $('#apply_branch_id').val(),
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                dataType: 'json',
                method: 'POST',
                processResults: function (data) {
                    data.page = data.page || 1;
                    if (area == 0){
                        data.data.unshift({
                            area_id : -1,
                            area_name : qrCode.jsonLang['Tất cả'],
                        });
                        area = 1;
                    }
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.area_id,
                                text: item.area_name,
                            };
                        }),
                        pagination: {
                            more: data.current_page + 1
                        }
                    };
                },
            }
        }).on('select2:select', function (e) {
            $('#apply_table_id').val('').trigger('change');
        }).on('select2:opening', function (e) {
            area = 0;
        });

        $('#apply_table_id').select2({
            dropdownParent: $('#apply_table_id').parent(),
            width: '100%',
            placeholder: qrCode.jsonLang['Chọn bàn'],
            ajax: {
                url: laroute.route('fnb.qr-code.list-table'),
                data: function (params) {
                    return {
                        area_id : $('#apply_arear_id').val(),
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                dataType: 'json',
                method: 'POST',
                processResults: function (data) {
                    data.page = data.page || 1;
                    if (table == 0){
                        data.data.unshift({
                            table_id : -1,
                            name : qrCode.jsonLang['Tất cả'],
                        });
                        table = 1;
                    }
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.table_id,
                                text: item.name,
                            };

                        }),
                        pagination: {
                            more: data.current_page + 1
                        }
                    };
                },
            }
        }).on('select2:opening', function (e) {
            table = 0;
        });

    },

    submitQrCode : function (qrCodeId = null){
        var frame = $('.list-frames li.active').data('frame-id');
        var logo = $('.list-logo li.active').data('image-logo');

        frame_id = frame;
        logo = logo;
        text = $('#scan-text').val();
        color = $('.jscolor').attr('data-current-color');

        $.ajax({
            url: laroute.route("fnb.qr-code.submit-qr-code"),
            method: "POST",
            data: $('#form-qr-code').serialize()+'&template_frames_id='+frame_id+'&template_logo=' +
                logo+'&template_content='+text+'&template_color='+color,
            success: function (res) {
                if (res.error == false){
                    swal(res.message, '', "success").then(function (){
                        window.location.href = laroute.route('fnb.qr-code');
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function (response) {
                var mess_error = '';
                $.map(response.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(mess_error, '', "error");
            }
        });
    },

    getClientIp : function (){
        $.ajax({
            url: laroute.route("fnb.qr-code.get-client-ip"),
            method: "POST",
            data: {},
            success: function (res) {
                if (res.error == false){
                    swal(res.message, '', "success");
                    $('.wifi_ip').val(res.ip);
                } else {
                    swal(res.message, '', "error");
                }
            },
        });
    },

    viewQrCode : function (){
        var frame = $('.list-frames li.active').data('frame-id');
        var logo = $('.list-logo li.active').data('image-logo');
        var font = $('#template_font_id option:selected').data('value');
        $.ajax({
            url: laroute.route("fnb.qr-code.view-qr-code"),
            method: "POST",
            data: {
                frame_id : frame,
                logo : logo,
                text : $('#scan-text').val(),
                color : $('.jscolor').attr('data-current-color'),
                font : font,
            },
            success: function (res) {
                if (res.error == false){
                    $('.box-qr-code').empty();
                    $('.box-qr-code').append(res.view);
                }
            },
        });

    },

    removeQrCode : function (obj,id){
        swal({
            title: qrCode.jsonLang['Thông báo'],
            text: qrCode.jsonLang['Khi xóa bạn không thể khôi phục lại'],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: qrCode.jsonLang['Xóa'],
            cancelButtonText: qrCode.jsonLang['Hủy'],
            onClose: function() {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("fnb.qr-code.remove"),
                    method: "POST",
                    data: {
                        id : id
                    },
                    success: function (res) {
                        if (res.error == false){
                            swal(res.message, '', "success").then(function (){
                                $('#autotable').PioTable('refresh');
                            });
                        } else {
                            swal(res.message, '', "error");
                        }
                    },
                });
            }
        });
    },

    editQrCode : function (){
        $.ajax({
            url: laroute.route("fnb.qr-code.update"),
            method: "POST",
            // data: {
            //     qr_code_template_id : $('#qr_code_template_id').val(),
            //     status : $('#status').val()
            // },
            data: $('#form-qr-code').serialize(),
            success: function (res) {
                if (res.error == false){
                    swal(res.message, '', "success").then(function (){
                        window.location.href = laroute.route('fnb.qr-code');
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function (response) {
                var mess_error = '';
                $.map(response.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(mess_error, '', "error");
            }
        });
    },

    download : function (){
        var type = $('input[name="download"]:checked').val();
        if(type == 'png'){
            qrCode.downloadSVGAsPNG();
        } else {
            qrCode.downloadSVGAsSVG();
        }
    },

    preview : function (page = 'detail' , id = null){

        if (page == 'created'){
            var frame = $('.list-frames li.active').data('frame-id');
            var logo = $('.list-logo li.active').data('image-logo');
            var font = $('#template_font_id option:selected').data('value');
            text = $('#scan-text').val();

            var color = $('.jscolor').data('current-color');
            color = color.replace('#','');
            var link = laroute.route('fnb.qr-code.preview',{frame_id : frame, logo : logo , color : color, font : font, text : text});
        } else {
            var link = laroute.route('fnb.qr-code.preview',{qr_code_template_id : id});

        }
        window.open(link, '_blank');

    },

    print: function (){
        var divToPrint=document.getElementById('box-qr-code-download');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();

        setTimeout(function(){newWin.close();},10);
    },

    downloadSVGAsSVG : function (){
        $('.box-qr-code-download .block-qr').map(function () {
            const svg = this.querySelector('svg');
            const xml = new XMLSerializer().serializeToString(svg);
            // const svg64 = btoa(xml); //for utf8: btoa(unescape(encodeURIComponent(xml)))
            const svg64 = btoa(unescape(encodeURIComponent(xml))); //for utf8: btoa(unescape(encodeURIComponent(xml)))
            const b64start = 'data:image/svg+xml;base64,';
            const image64 = b64start + svg64;
            var a = document.createElement("a"); //Create <a>
            a.href = image64; //Image Base64 Goes here
            a.download = "Image.svg"; //File name Here
            a.click(); //Downloaded file
        });


    },

    downloadSVGAsPNG : function (e){

        $('.box-qr-code-download .block-qr').map(function () {
            const svg = this.querySelector('svg');
            const xml = new XMLSerializer().serializeToString(svg);
            // const svg64 = btoa(xml); //for utf8: btoa(unescape(encodeURIComponent(xml)))
            const svg64 = btoa(unescape(encodeURIComponent(xml))); //for utf8: btoa(unescape(encodeURIComponent(xml)))
            const b64start = 'data:image/svg+xml;base64,';
            const image64 = b64start + svg64;
            imageUtil.base64SvgToBase64Png(image64, 200).then(pngSrc => {
                const a = document.createElement("a"); //Create <a>
                a.href = pngSrc; //Image Base64 Goes here
                a.download = "Image.png"; //File name Here
                a.click(); //Downloaded file
            });
        });
    }
}

var configColumn = {
    showPopupConfig : function (){
        $.ajax({
            url: laroute.route("fnb.qr-code.show-popup-config"),
            method: "POST",
            data: {},
            success: function (res) {
                if (res.error == false){
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#modal-config').modal('show');
                }
            },
        });
    },

    saveConfig : function (){
        $.ajax({
            url: laroute.route("fnb.qr-code.save-config"),
            method: "POST",
            data: $('#form-config').serialize(),
            success: function (res) {
                if (res.error == false){
                    swal(res.message, '', "success").then(function (){
                        location.reload();
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
        });
    },

    searchTable : function (page = 1){
        perpage = 10;
        $.ajax({
            url: laroute.route("fnb.qr-code.search-table"),
            method: "POST",
            data: $('#search-table').serialize()+'&page='+page+'&perpage='+perpage,
            success: function (res) {
                if (res.error == false){
                    $('.append-table-table').empty();
                    $('.append-table-table').append(res.view);
                } else {
                    swal(res.message, '', "error");
                }
            },
        });
    }
}

function uploadAvatar(input){
    var arr = ['.jpg', '.png', '.jpeg', '.JPG', '.PNG', '.JPEG'];
    var check = 0;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var file_data = $('#getFile').prop('files')[0];

        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_qr-code.');
        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();
        $.map(arr, function (item) {
            if (file_data.name.indexOf(item) != -1) {
                check = 1;
            }
        })
        if (check == 1) {
            if (Math.round(fsize / 1024) <= 10240) {
                reader.onload = function (e) {
                    $('#blah_en')
                        .attr('src', e.target.result);

                };
                reader.readAsDataURL(input.files[0]);
                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        $('#image').val(res.file);
                        $('.sample-picture').show();
                    },
                    error: function (res) {
                        swal.fire(qrCode.jsonLang["Hình ảnh không đúng định dạng"], "", "error");
                    }
                });
            } else {
                swal.fire(qrCode.jsonLang["Hình ảnh vượt quá dung lượng cho phép"], "", "error");
            }
        } else {
            swal.fire(qrCode.jsonLang["Hình ảnh không đúng định dạng"], "", "error");
        }
    }
}

function uploadLogo(input){
    var arr = ['.jpg', '.png', '.jpeg', '.JPG', '.PNG', '.JPEG'];
    var check = 0;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var file_data = $('#getFileLogo').prop('files')[0];

        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_qr-code.');
        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();
        $.map(arr, function (item) {
            if (file_data.name.indexOf(item) != -1) {
                check = 1;
            }
        })
        if (check == 1) {
            if (Math.round(fsize / 1024) <= 10240) {
                reader.onload = function (e) {
                    $('#blah_en')
                        .attr('src', e.target.result);

                };
                reader.readAsDataURL(input.files[0]);
                $.ajax({
                    url: laroute.route("fnb.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        $('.list-logo').append(res.view);
                        $('.list-logo li').click(function (){
                            $('.list-logo li').removeClass('active');
                            $(this).addClass('active');
                            qrCode.viewQrCode();
                        });
                    },
                    error: function (res) {
                        swal.fire(qrCode.jsonLang["Hình ảnh không đúng định dạng"], "", "error");
                    }
                });
            } else {
                swal.fire(qrCode.jsonLang["Hình ảnh vượt quá dung lượng cho phép"], "", "error");
            }
        } else {
            swal.fire(qrCode.jsonLang["Hình ảnh không đúng định dạng"], "", "error");
        }
    }
}


function deleteImage(){
    $('.sample-picture').hide();
    $('#image').val('');
}

$(document).ready(function (){
    // Ẩn hiện áp dụng cho
    $('input[name="apply_for"]').change(function (){
        if(this.value == 'custom'){
            $('.apply_block').show();
        } else {
            $('.apply_block').hide();
        }
    });

    // Thời gian hiệu lực
    $('input[name="expire_type"]').change(function (){
        if(this.value == 'limited'){
            $('.expire_type').show();
        } else {
            $('.expire_type').hide();
        }
    });

    // Yêu cầu vị trí
    $('.is_request_location').change(function (){
        if($('.is_request_location').is(':checked')){
            $('.is_request_location_block').show();
        } else {
            $('.is_request_location_block').hide();
        }
    });

    // Yêu cầu đăng nhập wifi
    $('.is_request_wifi').change(function (){
        if($('.is_request_wifi').is(':checked')){
            $('.is_request_wifi_block').show();
        } else {
            $('.is_request_wifi_block').hide();
        }
    });

    $('.list-frames li').click(function (){
        $('.list-frames li').removeClass('active');
        $(this).addClass('active');
        qrCode.viewQrCode();
    });

    $('.list-logo li').click(function (){
        $('.list-logo li').removeClass('active');
        $(this).addClass('active');
        qrCode.viewQrCode();
    });

    $('.jscolor').change(function (){
        qrCode.viewQrCode();
    })
});

function getColorCode() {
    qrCode.viewQrCode();
}

/**
 * converts a base64 encoded data url SVG image to a PNG image
 * @param originalBase64 data url of svg image
 * @param width target width in pixel of PNG image
 * @param secondTry used internally to prevent endless recursion
 * @return {Promise<unknown>} resolves to png data url of the image
 */
imageUtil.base64SvgToBase64Png = function (originalBase64, width, secondTry) {
    return new Promise(resolve => {
        let img = document.createElement('img');
        img.onload = function () {
            if (!secondTry && (img.naturalWidth === 0 || img.naturalHeight === 0)) {
                let svgDoc = base64ToSvgDocument(originalBase64);
                let fixedDoc = fixSvgDocumentFF(svgDoc);
                return imageUtil.base64SvgToBase64Png(svgDocumentToBase64(fixedDoc), width, true).then(result => {
                    resolve(result);
                });
            }
            document.body.appendChild(img);
            let canvas = document.createElement("canvas");
            let ratio = (img.clientWidth / img.clientHeight) || 1;
            document.body.removeChild(img);
            canvas.width = width;
            canvas.height = width / ratio;
            let ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            try {
                let data = canvas.toDataURL('image/png');
                resolve(data);
            } catch (e) {
                resolve(null);
            }
        };
        img.src = originalBase64;
    });
}

//needed because Firefox doesn't correctly handle SVG with size = 0, see https://bugzilla.mozilla.org/show_bug.cgi?id=700533
function fixSvgDocumentFF(svgDocument) {
    try {
        let widthInt = parseInt(svgDocument.documentElement.width.baseVal.value) || 500;
        let heightInt = parseInt(svgDocument.documentElement.height.baseVal.value) || 500;
        svgDocument.documentElement.width.baseVal.newValueSpecifiedUnits(SVGLength.SVG_LENGTHTYPE_PX, widthInt);
        svgDocument.documentElement.height.baseVal.newValueSpecifiedUnits(SVGLength.SVG_LENGTHTYPE_PX, heightInt);
        return svgDocument;
    } catch (e) {
        return svgDocument;
    }
}

function svgDocumentToBase64(svgDocument) {
    try {
        let base64EncodedSVG = btoa(new XMLSerializer().serializeToString(svgDocument));
        return 'data:image/svg+xml;base64,' + base64EncodedSVG;
    } catch (e) {
        return null;
    }
}

function base64ToSvgDocument(base64) {
    let svg = atob(base64.substring(base64.indexOf('base64,') + 7));
    svg = svg.substring(svg.indexOf('<svg'));
    let parser = new DOMParser();
    return parser.parseFromString(svg, "image/svg+xml");
}