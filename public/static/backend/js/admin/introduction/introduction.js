function update (id) {
    $.getJSON(laroute.route('translate'), function (json) {
        $.ajax({
            url: laroute.route('admin.config.update.introduction'),
            method: "POST",
            data: {
                id: id,
                description: $('#description').summernote('code')
            },
            dataType: "JSON",
            success: function (data) {
                swal.fire(json['Cập nhật thành công'], "", "success").then(function (res) {
                    location.reload();
                });
            }
        })
    });
}
