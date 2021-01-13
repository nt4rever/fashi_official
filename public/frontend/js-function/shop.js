$(function () {
    $('.nav-menu ul li').removeClass('active');
    $('.nav-menu ul li').eq(1).addClass('active');

    var url = $('#myurl').attr('url');

    $('.p-show.show-qty').on('change', function () {
        var url = $(this).val();
        if (url) {
            window.location.href = url;
        }
        return false;
    });

    $('.sorting.cus-sort').on('change', function () {
        var url = $(this).val();
        if (url) {
            window.location.href = url;
        }
        return false;
    });

    $(document).on('click', '.home_quick_view', function () {
        var id = $(this).data('id');
        var _token = $('input[name=_token]').val();
        topbar.show();

        (function step() {
            setTimeout(function () {
                if (topbar.progress('+.01') < 1) step()
            }, 16)
        })()
        $.ajax({
            type: "POST",
            cache: false,
            url: url + "/quick-view",
            data: { product_id: id, _token: _token },
            dataType: "html",
            success: function (data) {
                topbar.hide()
                $('.modal-load-quick-view').html(data);
                $('#modal-home').modal('show');
                var proQty = $('.pro-qty');
                proQty.prepend('<span class="dec qtybtn">-</span>');
                proQty.append('<span class="inc qtybtn">+</span>');
                proQty.on('click', '.qtybtn', function () {
                    var $button = $(this);
                    var oldValue = $button.parent().find('input').val();
                    if ($button.hasClass('inc')) {
                        var newVal = parseFloat(oldValue) + 1;
                    } else {
                        // Don't allow decrementing below zero
                        if (oldValue > 1) {
                            var newVal = parseFloat(oldValue) - 1;
                        } else {
                            newVal = 1;
                        }
                    }
                    $button.parent().find('input').val(newVal);
                });

                $('.product-thumbs-track .pt').on('click', function () {
                    $('.product-thumbs-track .pt').removeClass('active');
                    $(this).addClass('active');
                    var imgurl = $(this).data('imgbigurl');
                    var bigImg = $('.product-big-img').attr('src');
                    if (imgurl != bigImg) {
                        $('.product-big-img').attr({ src: imgurl });
                        $('.zoomImg').attr({ src: imgurl });
                    }
                });

                $('.product-pic-zoom').zoom();
            }
            ,
            error: function (error) {
                topbar.hide()
                swal("Erorr!");
            }
        });
        return false;
    });

    $(document).on('click', 'a.add_to_cart', function () {
        var id = $(this).data('product_id');
        var _token = $("input[name='_token']").val();
        var qty = $(this).parent().children().find('.product_qty').val();
        if (!qty) {
            qty = 1;
        }
        swal({
            title: "Thêm sản phẩm này vào giỏ hàng?",
            text: "Thank you!",
            icon: "info",
            buttons: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    topbar.show();
                    (function step() {
                        setTimeout(function () {
                            if (topbar.progress('+.01') < 1) step()
                        }, 16)
                    })();
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: url + "/add-cart",
                        data: { product_id: id, product_qty: qty, _token: _token },
                        dataType: "json",
                        success: function (data) {
                            topbar.hide()
                            if (data.status == true) {
                                swal(data.message, {
                                    icon: "success",
                                });
                                $('#cart_mini').html(data.cart);
                            }
                            else {
                                swal(data.message, {
                                    icon: "error",
                                });
                            }
                        }
                        ,
                        error: function (error) {
                            topbar.hide()
                            swal("Erorr!");
                        }
                    });

                }
            });
        return false;
    });
});