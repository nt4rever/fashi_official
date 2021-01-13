$(function () {
    $("button[name = 'delete_brand']").click(function () {
        swal({
            title: "Bạn chắc chắn muốn xoá mục này?",
            text: "Không thể khôi phục khi xoá!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    var id = $(this).data('brandid');
                    var display = $(this).parent().parent();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "delete-brand",
                        data: { brand_id: id },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == true) {
                                display.remove();
                                swal(data.message, {
                                    icon: "success",
                                });
                            } else {
                                swal(data.message, {
                                    icon: "warning",
                                });
                            }
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