Dropzone.options.dropzoneone={
    url: laroute.route('admin.store.uploads'),
    maxFiles:1,
    paramName:"store_image",
    uploadMultiple: false,
    acceptedFile:'image/*',
    headers:{
        "X-CSRF-TOKEN": $('input[name=_token]').val()
    },
    addRemoveLinks:true,
    maxFilesize:3,
    init:function () {
        this.on('success',function (file,response) {
            if(response.success ==1)
                if($('#file_image').length){
                    document.getElementById('file_image').value=response.file;
                }else{
                    $("#form").prepend("<input id='file_image' type='hidden' name='store_image' value='"+ response.file+"'>");
                }

        });
        this.on('error',function (file,response) {
            $(file.previewElement).find(".dz-error-message").html(response.message)
        });
        this.on('removedfile',function (file) {
            $.ajax({
                url:laroute.route('admin.store.delete'),
                method: "POST",
                data:{
                    filename:$("#file_image").val()
                },
                success:function (data) {
                    
                }
            })
        });

        var file={name:"store_image",size:10000,dataURL:"uploads/admin/store/" + "store_image" };
        this.emit("addedfile",file);
        this.files.push(file);
        this.createThumbnailFromUrl(file,
            this.options.thumbnailWidth,this.options.thumbnailHeight,
            this.options.thumbnailMethod,true,
            function (thumbnail)
            {
               this.emit('thumbnail',file,thumbnail);
            });
        this.emit("complete",file);
        var existingFileCount = 1;
        this.options.maxFiles = this.options.maxFiles - existingFileCount;



    }
}