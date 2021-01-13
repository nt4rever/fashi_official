$(function () {
    $('#form_gallery').change(function () {
        var error = "";
        var files = $('#file')[0].files;
        if (files.length > 5) {
            error = "Tối đa chỉ 5 ảnh";
        } else if (files.length = '') {
            error = "Không được bỏ trống ảnh";
        } else if (files.size > 2000000) {
            error = "Kích thước ảnh quá 2MB";
        }
        if (error == "") {
            $('#error_gallery').html(error);
        } else {
            $('#file').val('');
            $('#error_gallery').html(error);
        }
    });

    $(document).on('blur', '.edit_gallery_name', function () {
        var gal_id = $(this).data('gal_id');
        var gal_text = $(this).text();
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "POST",
            cache: false,
            url: "{{url('/update-product-gallery-name')}}",
            data: { gallery_id: gal_id, gallery_text: gal_text, _token: _token },
            dataType: "html",
            success: function (data) {
                console.log(data)
            }
            ,
            error: function (error) {
                swal("Erorr!");
            }
        });
    });

    $("button[name = 'delete_gallery']").click(function () {
        swal({
            title: "Bạn chắc chắn muốn xoá?",
            text: "Sản phẩm này sẽ biến mất vĩnh viển!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    var gal_id = $(this).data('gal_id');
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    var this_tr = $(this).parent().parent();
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "{{url('/delete-product-gallery')}}",
                        data: { gallery_id: gal_id, _token: _token },
                        dataType: "html",
                        success: function (data) {
                            console.log(data);
                            this_tr.remove();
                        }
                        ,
                        error: function (error) {
                            swal("Erorr!");
                        }
                    });
                }
            });
    });
});

