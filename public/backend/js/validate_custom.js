$(document).ready(function () {
    // $.validator.setDefaults({
    //     submitHandler: function () {
    //         alert("Form successful submitted!");
    //     }
    // });

    $('#form_add_category').validate({
        rules: {
            category_name: {
                required: true,
                minlength: 2,
                maxlength: 50,
            },
            category_status: {
                required: true
            }
        },
        messages: {
            category_name: {
                required: "Vui lòng nhập tên danh mục",
                minlength: "Vui lòng hơn 5 kí tự",
                maxlength: "Vui lòng không nhập quá 50 kí tự"
            },
            category_status: {
                required: "Vui lòng chọn trạng thái"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    $('#form_add_brand').validate({
        rules: {
            brand_name: {
                required: true,
                minlength: 2,
                maxlength: 50,
            },
            brand_status: {
                required: true
            }
        },
        messages: {
            brand_name: {
                required: "Vui lòng nhập tên thương hiệu",
                minlength: "Vui lòng hơn 5 kí tự",
                maxlength: "Vui lòng không nhập quá 50 kí tự"
            },
            brand_status: {
                required: "Vui lòng chọn trạng thái"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });


    $('#form_add_product').validate({
        rules: {
            product_name: {
                required: true,
                minlength: 1,
                maxlength: 100,
            },
            product_price: {
                minlength: 1,
                maxlength: 100,
                required: true
            },
            product_quantity: {
                required: true
            },
            product_image: {
                required: true,
                extension: "jpg|jpeg|png|JPG|JPEG|PNG"
            }
        },
        messages: {
            product_name: {
                required: "Vui lòng nhập tên sản phẩm",
                minlength: "Vui lòng hơn 5 kí tự",
                maxlength: "Vui lòng không nhập quá 50 kí tự"
            },
            product_price: {
                min: "Vui lòng nhập giá lớn hơn 1000đ",
                required: "Vui lòng nhập giá"
            },
            product_quantity: {
                required: "Vui lòng nhập số lượng"
            },
            product_image: {
                required: "Vui lòng chọn ảnh",
                extension: "Vui lòng chọn đúng định dạng ảnh"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    $('#form_edit_product').validate({
        rules: {
            product_name: {
                required: true,
                minlength: 5,
                maxlength: 100,
            },
            product_price: {
                minlength: 1,
                maxlength: 100,
                required: true
            },
            product_quantity: {
                min: 0,
                required: true
            },
            product_image: {
                extension: "jpg|jpeg|png|JPG|JPEG|PNG"
            }
        },
        messages: {
            product_name: {
                required: "Vui lòng nhập tên sản phẩm",
                minlength: "Vui lòng hơn 5 kí tự",
                maxlength: "Vui lòng không nhập quá 50 kí tự"
            },
            product_price: {
                // min: "Vui lòng nhập giá lớn hơn 1000đ",
                required: "Vui lòng nhập giá"
            },
            product_quantity: {
                required: "Vui lòng nhập số lượng"
            },
            product_image: {
                extension: "Vui lòng chọn đúng định dạng ảnh"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});