// const { data } = require("jquery");


$(function () {
    $("button[name = 'delete_product']").click(function () {
        swal({
            title: "Bạn chắc chắn muốn xoá?",
            text: "Sản phẩm này sẽ biến mất vĩnh viển!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    var product = $(this).parent().parent();
                    var id = product.children(":first").text();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "delete-product",
                        data: { product_id: id },
                        dataType: "json",
                        success: function (datajson) {
                            var data = JSON.stringify(datajson);
                            if (data === 'true') {
                                swal("Xoá thành công!", "Nhấn OK để thoát!", "success");
                                product.remove();
                            }
                            else {
                                swal('Đã xảy ra ỗi!');
                            }
                        },
                        error: function (error) {
                            swal("Erorr!");
                        }
                    });
                }
            });

    });

    $('.change_status_product').click(function () {
        var product = $(this).parent().parent();
        var display = $(this);
        var id = product.children(":first").text();
        var status = $(this).data('status');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            cache: false,
            url: "change-status-product",
            data: { product_id: id, product_status: status, _token: _token },
            dataType: "html",
            success: function (datajson) {
                if (datajson == true) {
                    if (status == 0) {
                        display.data('status', 1)
                        display.text('Ẩn');
                    }
                    else {
                        display.data('status', 0)
                        display.text('Hiển thị');
                    }
                }
            }
            ,
            error: function (error) {
                swal("Erorr!");
            }
        });

        return false;
    });

    $('select[name=select_quantity_view]').on('change', function () {
        var quantity = $(this).val();
        var url = $('input[name=my_url]').val();
        window.location.href = url + '/list-product?view=' + quantity;
    });

    $('#product_order').sortable({
        placeholder: 'ui-state-highlight',
        update: function (event, ui) {
            var _token = $('input[name=_token]').val();
            var url = $('input[name=my_url]').val();
            var page_id_array = new Array();
            $('#product_order tr').each(function () {
                page_id_array.push($(this).attr('id'));
            });

            $.ajax({
                type: "POST",
                url: url + '/arrange-product',
                data: { page_id_array: page_id_array, _token: _token },
                dataType: "html",
                success: function (data) {
                    console.log(data);
                }
                ,
                error: function (error) {
                    console.log(error);
                    swal("Erorr!");
                }
            });
            console.log(page_id_array);
        }
    });
});