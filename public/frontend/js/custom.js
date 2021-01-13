$(document).ready(function ($) {
    var myurl = $('#myurl').attr('url');

    $('select[name=category]').on('change', function(){
        $('input[name=keyword]').val('');
    });

    var engine1 = new Bloodhound({
        remote: {
            wildcard: '%QUERY%',
            url: myurl + '/search/name?value=%QUERY%',
            replace: function (url, uriEncodedQuery) {
                val = $('select[name=category]').val();
                if (!val) return url.replace("%QUERY%", uriEncodedQuery);
                return url.replace("%QUERY%", uriEncodedQuery) + '&category=' + encodeURIComponent(val);
            }
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });

    var engine2 = new Bloodhound({
        remote: {
            wildcard: '%QUERY%',
            url: myurl + '/search/price?value=%QUERY%',
            replace: function (url, uriEncodedQuery) {
                val = $('select[name=category]').val();
                if (!val) return url.replace("%QUERY%", uriEncodedQuery);
                return url.replace("%QUERY%", uriEncodedQuery) + '&category=' + encodeURIComponent(val);
            }
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });



    $(".search-input").typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, [
        {
            source: engine1.ttAdapter(),
            name: 'products-name',
            display: function (data) {
                return data.product_name;
            },
            templates: {
                empty: [
                    '<div class="header-title">Name</div><div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="header-title">Name</div><div class="list-group search-results-dropdown search-dropdown"></div>'
                ],
                suggestion: function (data) {
                    return '<a href="' + myurl + '/product/' + data.product_slug + '" class="list-group-item search-item">' + data.product_name + ' (' + data.product_price_discount + ' đ)</a>';
                }
            }
        },
        {
            source: engine2.ttAdapter(),
            name: 'product_price',
            display: function (data) {
                return data.product_price;
            },
            templates: {
                empty: [
                    '<div class="header-title">Price</div><div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="header-title">Price</div><div class="list-group search-results-dropdown search-dropdown"></div>'
                ],
                suggestion: function (data) {
                    return '<a href="' + myurl + '/product/' + data.product_slug + '" class="list-group-item search-item">' + data.product_name + ' (' + data.product_price_discount + ' đ)</a>';
                }
            }
        }
    ]);
});