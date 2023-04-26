$('#content-notification').summernote({
    placeholder: 'Nhập nội dung',
    tabsize: 2,
    height: 300,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname', 'fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
    ]
    // onImageUpload: function(files, editor, welEditable) {
    //     sendFile(files[0], editor, welEditable);
    // }
});
// function sendFile(file, editor, welEditable)
// {
//     data = new FormData();
//     data.append("file", file);
//     $.ajax({
//         data: data,
//         type: "POST",
//         url: "summernot-image.php",
//         cache: false,
//         contentType: false,
//         processData: false,
//         success: function (url) {
//             editor.insertImage(welEditable, url);
//         }
//     });
// }
//
// $(document).ready(function () {
//     $(".blog-type").click(function (e) {
//         var type = $(this).val();
//         if (type == 0) {
//             $(".check-category").prop('disabled', true);
//         } else {
//             $(".check-category").prop('disabled', false);
//         }
//     });
// });