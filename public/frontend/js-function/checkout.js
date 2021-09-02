$(function () {
    $('.nav-menu ul li').removeClass('active');
    $('.nav-menu ul li:contains(Shopping Cart)').addClass('active');

    var url = $('#myurl').attr('url');

    $(document).on('change', 'select[name=thanhpho]', function () {
        var thanhpho = $(this).find(':selected').data('id');
        var _token = $('input[name=_token]').val();
        $.ajax({
            type: "POST",
            cache: false,
            url: url + "/get-district-data",
            data: { thanhpho: thanhpho, _token: _token },
            dataType: "html",
            success: function (data) {
                $('select[name=quanhuyen]').html(data);
                $('select[name=xaphuong]').html('<option value="" disabled selected>Chọn xã - phường</option>');
            }
            ,
            error: function (error) {
                swal("Erorr!");
            }
        });
    });

    $(document).on('change', 'select[name=quanhuyen]', function () {
        var quanhuyen = $(this).find(':selected').data('id');
        var _token = $('input[name=_token]').val();
        $.ajax({
            type: "POST",
            cache: false,
            url: url + "/get-wards-data",
            data: { quanhuyen: quanhuyen, _token: _token },
            dataType: "html",
            success: function (data) {
                $('select[name=xaphuong]').html(data);

            }
            ,
            error: function (error) {
                swal("Erorr!");
            }
        });
    });
});