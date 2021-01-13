$(function () {
    $("button[name = 'delete_category']").click(function () {

        swal({
            title: "Bạn chắc chắn muốn xoá mục này?",
            text: "Không thể khôi phục khi xoá!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    var id = $(this).data('categoryid');
                    var display = $(this).parent().parent();
                    var _token = $('input[name=_token]').val();
                    var url = $('input[name=my_url]').val();
                    url = url + '/delete-category';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: { category_id: id, _token: _token },
                        dataType: "html",
                        success: function (datajson) {
                            if (datajson == "true") {
                                display.remove();
                                swal("Xoá thành công", {
                                    icon: "success",
                                });
                            }
                            else {
                                swal(datajson);
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

    $('#category_order').sortable({
        placeholder: 'ui-state-highlight',
        update: function (event, ui) {
            var _token = $('input[name=_token]').val();
            var url = $('input[name=my_url]').val();
            var page_id_array = new Array();
            $('#category_order tr').each(function () {
                page_id_array.push($(this).attr('id'));
            });

            $.ajax({
                type: "POST",
                url: url + '/arrange-category',
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
        }
    });
});