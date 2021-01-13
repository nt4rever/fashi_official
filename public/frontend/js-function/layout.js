function add_wishlist(clicked_id) {
    var id = clicked_id;
    var name = document.getElementById('wishlist_product_name_' + id).innerHTML;
    var price = document.getElementById('wishlist_product_price_' + id).innerHTML;
    var url = document.getElementById('wishlist_product_url_' + id).href;
    var img = document.getElementById('wishlist_product_img_' + id).src;
    var newItem = {
        'url': url,
        'id': id,
        'name': name,
        'price': price,
        'img': img
    }
    if (localStorage.getItem('data') == null) {
        localStorage.setItem('data', '[]');

    }
    var old_data = JSON.parse(localStorage.getItem('data'));
    var matches = $.grep(old_data, function (obj) {
        return obj.id == id;
    });

    if (matches.length) {

        toastr["warning"]('Sản phẩm đã tồn tại trong danh sách yêu thích!');
    } else {
        toastr.info('Thêm vào danh sách yêu thích thành công!')
        old_data.push(newItem);
    }
    localStorage.setItem('data', JSON.stringify(old_data));
    view();
}

function view() {
    if (localStorage.getItem('data') != null && localStorage.getItem('data') != '[]') {
        $("#list-favorite-item").html('');
        var data = JSON.parse(localStorage.getItem('data'));
        data.reverse();
        for (i = 0; i < data.length; i++) {
            var name = data[i].name;
            var price = data[i].price;
            var img = data[i].img;
            var url = data[i].url;
            $("#list-favorite-item").append('<tr><td class="si-pic"><img src="' + img + '" alt="" width="50"></td> <td class="si-text"><div class="product-selected"><p>' + price + '</p><h6><a href="' + url + '">' + name + '</a></h6></div></td><td class="si-close fvr" id="' + i + '"><i class="ti-close" ></i></td></tr>');
        }
        $('#count-favorite-item').html(data.length);
    }
    else {
        $("#list-favorite-item").append('<p>Chưa có sản phẩm yêu thích!</p>');
        $('#count-favorite-item').html(0);
    }
}
view();
$(function () {
    $('#delete-favorite-item').click(function () {
        localStorage.setItem('data', '[]');
        $("#list-favorite-item").html('<p>Chưa có sản phẩm yêu thích!</p>');
        $('#count-favorite-item').html(0);
        return false;
    });
    $(document).on('click', '.si-close.fvr', function () {
        var id = $(this).attr('id');
        if (localStorage.getItem('data') != null && localStorage.getItem('data') != '[]') {
            var data = JSON.parse(localStorage.getItem('data'));
            data.splice(id, 1);
            localStorage.setItem('data', JSON.stringify(data));
            $(this).parent().remove();
            view();
        }
    });
})