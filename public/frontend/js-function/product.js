$(function () {
    $('.nav-menu ul li').removeClass('active');
    $('.nav-menu ul li').eq(1).addClass('active');

    var url = $('#myurl').attr('url');
    $('#customer-review-option').on('click', "button[name = 'comment_submit']", function () {
        // get time client
        function AddZero(num) {
            return (num >= 0 && num < 10) ? "0" + num : num + "";
        }
        var now = new Date();
        var strDateTime = [[AddZero(now.getDate()),
        AddZero(now.getMonth() + 1),
        now.getFullYear()].join("/"),
        [AddZero(now.getHours()),
        AddZero(now.getMinutes())].join(":"),
        now.getHours() >= 12 ? "PM" : "AM"].join(" ");

        //set variable
        var comment_time = strDateTime;
        var content = $("textarea[name = 'comment_content']").val();
        var id = $(this).data('product_id');
        var _token = $("input[name = '_token']").val();
        if (content == "") {
            swal("Please fill name and message!");
            return false;
        }

        var reply = $('input[name=reply_to]').data('id');


        //send ajax to server
        topbar.show();

        (function step() {
            setTimeout(function () {
                if (topbar.progress('+.01') < 1) step()
            }, 16)
        })()
        $.ajax({
            type: "POST",
            cache: false,
            url: url + "/add-product-comment",
            data: {
                product_id: id,
                comment_content: content, comment_time: comment_time, reply: reply, _token: _token
            },
            dataType: "json",
            success: function (data) {
                topbar.hide();
                $('.comment-option').html(data.comment);
                $('.count_comment').text(data.count);
            }
            ,
            error: function (error) {
                topbar.hide();
                alert("Error!")
            }
        });

        //clear value form
        $("textarea[name = 'comment_content']").val("");
        return false;
    });


    $('#customer-review-option').on('click', '.comment-reply', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('input[name=reply_to]').val('Reply: ' + name);
        $('input[name=reply_to]').data('id', id);
        $("textarea[name = 'comment_content']").focus();
        return false;
    });

    $('#customer-review-option').on('click', 'button.cancel_reply', function () {
        $('input[name=reply_to]').val('Reply all');
        $('input[name=reply_to]').data('id', '');
        return false;
    });

    $('#customer-review-option').on('click', '.comment-remove', function () {
        var id = $(this).data('id');
        var _token = $("input[name = '_token']").val();
        swal({
            text: "Bạn chắc chắn muốn xoá comment này!",
            buttons: true,
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
                        url: url + "/delete-product-comment-customer",
                        data: {
                            comment_id: id, _token: _token
                        },
                        dataType: "json",
                        success: function (data) {
                            topbar.hide();
                            $('.comment-option').html(data.comment);
                            $('.count_comment').text(data.count);
                        }
                        ,
                        error: function (error) {
                            topbar.hide();
                            alert("Error!")
                        }
                    });
                }
            });
        return false;
    });

    $('a.primary-btn.pd-cart').click(function () {
        //set variable
        var id = $(this).data('product_id');
        var qty = $(this).parent().children().find('#product_qty').val();
        var attribute = $(this).parent().parent().children().find('select[name=attribute]').val();
        if (!attribute) {
            attribute = 'Default';
        }
        if (!qty) {
            qty = 1;
        } else if (qty < 1) {
            swal('Nhập số lượng sản phẩm!');
            return false;
        }



        var _token = $("input[name='_token']").val();

        //sweetalert confirm add product to cart
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
                    })()
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: url + "/add-cart",
                        data: { product_id: id, product_qty: qty, attribute: attribute, _token: _token },
                        dataType: "json",
                        success: function (data) {
                            // console.log(data);
                            topbar.hide();
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
                            topbar.hide();
                            swal("Erorr!");
                        }
                    });

                }
            });
        return false;
    });

    //change product price when select attribute
    $('select[name=attribute]').change(function () {
        //format number js
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
        var price = $(this).children(':selected').data('price');
        var this_price = $('.price-price-discount').data('price');
        $('.price-price-discount').html(formatNumber(price + this_price) + " đ");
    });

    $(document).on('mouseenter', '.rating-ajax', function () {
        var index = $(this).data('index');
        var product_id = $(this).data('product_id');
        remove_background(product_id);
        for (var count = 1; count <= index; count++) {
            $('#' + product_id + '-' + count).css('color', '#ffcc00');
        }
    });

    $(document).on('mouseleave', '.rating-ajax', function () {
        var index = $(this).data('index');
        var product_id = $(this).data('product_id');
        var rating = $(this).data('rating');
        remove_background(product_id);
        for (var count = 1; count <= rating; count++) {
            $('#' + product_id + '-' + count).css('color', '#ffcc00');
        }
    });

    function remove_background(product_id) {
        for (var count = 1; count <= 5; count++) {
            $('#' + product_id + '-' + count).css('color', '#ccc');
        }
    }

    $(document).on('click', '.rating-ajax', function () {
        var index = $(this).data('index');
        $('.rating-ajax').data('rating', index);
        var product_id = $(this).data('product_id');
        var _token = $("input[name='_token']").val();
        $.ajax({
            url: url + '/add-rating',
            method: "POST",
            data: { index: index, product_id: product_id, _token: _token },
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        })
    });
});