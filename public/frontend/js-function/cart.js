$(function () {
    $('.nav-menu ul li').removeClass('active');
    $('.nav-menu ul li').eq(6).addClass('active');

    var url = $('#myurl').attr('url');

    $('a.proceed-btn').click(function () {
        var count = $('.table-cart-body').children().length;
        if (count == 0) {
            swal('Chưa có sản phẩm trong giỏ hàng!');
        }
        else {
            window.location.href = url + '/checkout';
        }
        return false;
    });

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
    var _token = $("input[name='_token']").val();

    $('.dec.qtybtn').click(function () {
        var qty_input = $(this).parent().children().eq(1);
        var qty = qty_input.val();
        if (qty > 1) {
            qty--;
            qty_input.val(qty);
        }
        else if (qty == 1) {
            return false;
        }
        var this_total = $(this).parent().parent().parent().parent().children().eq(5);
        var price = $(this).parent().children().eq(1).data('price');
        var rowid = $(this).parent().children().eq(1).data('rowid');
        add_cart(rowid, qty, _token, this_total, price);
        return false;
    });

    $('.inc.qtybtn').click(function () {
        var qty_input = $(this).parent().children().eq(1);
        var qty = qty_input.val();
        qty++;
        qty_input.val(qty);
        var this_total = $(this).parent().parent().parent().parent().children().eq(5);
        var price = $(this).parent().children().eq(1).data('price');
        var rowid = $(this).parent().children().eq(1).data('rowid');
        add_cart(rowid, qty, _token, this_total, price);
        return false;
    });

    function add_cart(rowid, qty, _token, this_total, price) {
        topbar.show();

        (function step() {
            setTimeout(function () {
                if (topbar.progress('+.01') < 1) step()
            }, 16)
        })()
        $.ajax({
            type: "POST",
            cache: false,
            url: url + "/update-cart",
            data: { rowId: rowid, qty: qty, _token: _token },
            dataType: "json",
            success: function (data) {
                topbar.hide();
                if (data.status == true) {
                    this_total.html(formatNumber(price * qty) + " đ");
                    var cart_mini = data.cart_mini;
                    var sub_total = data.sub_total;
                    $('.proceed-checkout ul').html(sub_total);
                    $('#cart_mini').html(cart_mini);
                    $('.coupon_area').remove();
                    toastr.success(data.message);
                } else {
                    swal(data.message, {
                        icon: "error",
                    });
                }

            }
            ,
            error: function (error) {
                topbar.hide();
                swal("Erorr!");
            }
        });
    }

    $('.ti-close.delete-cart').click(function () {
        var rowid = $(this).data('rowid');
        var this_row = $(this).parent().parent();
        swal({
            title: "Xoá sản phẩm này vào giỏ hàng?",
            text: "Thank you!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    topbar.show();

                    (function step() {
                        setTimeout(function () {
                            if (topbar.progress('+.01') < 1) step()
                        }, 16)
                    })()
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: url + "/delete-cart",
                        data: { rowid: rowid, _token: _token },
                        dataType: "json",
                        success: function (data) {
                            topbar.hide();
                            var cart_mini = data.cart_mini;
                            var sub_total = data.sub_total;
                            $('.proceed-checkout ul').html(sub_total);
                            $('#cart_mini').html(cart_mini);
                            this_row.remove();
                            $('.coupon_area').remove();
                            swal("Xoá thành công!", {
                                icon: "success",
                            });
                        }
                        ,
                        error: function (error) {
                            topbar.hide();
                            swal("Erorr!");
                        }
                    });

                }
            });
    });
});