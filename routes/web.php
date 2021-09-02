<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryPostController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DataAddressController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeSliderController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\TagContronller;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//---------------------------------index FRONTEND for customer-----------------------------------------//
Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/shop', [HomeController::class, 'shop']);
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('getCategory');
Route::get('/product/{slug}', [HomeController::class, 'product']);
Route::get('/contact', [HomeController::class, 'contact']);
Route::get('/faq', [HomeController::class, 'faq']);
Route::get('/search-bar', [HomeController::class, 'search']);
Route::get('/filter-range', [HomeController::class, 'filter_range']);
Route::get('/list-order-customer', [HomeController::class, 'list_order']);
Route::get('/search/name', [SearchController::class, 'search_name']);
Route::get('/search/price', [SearchController::class, 'search_price']);
Route::post('/quick-view', [HomeController::class, 'quick_view']);
Route::get('/blog', [HomeController::class, 'blog']);

//---------------------------------end index FRONTEND for customer-----------------------------------------//

//-----------------------------------ADMIN--------------------------------------------------------------//
//admin dashboard - auth admin - login - logout
Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
Route::get('/admin', [AdminController::class, 'login_dashboard']);
Route::get('/admin-logout', [AdminController::class, 'logout_dashboard']);
Route::get('/login-auth', [AuthController::class, 'login_auth']);
Route::post('/login-auth-handle', [AuthController::class, 'login_auth_handle']);
Route::get('/ad/my-account/{id}', [AuthController::class, 'my_account']);
Route::post('/ad/save-my-account', [AuthController::class, 'save_my_account']);

//----role for just admin
Route::group(['middleware' => 'auth.admin'], function () {
    //account
    Route::get('/auth-users', [UserController::class, 'index']);
    Route::post('/assign-roles', [UserController::class, 'assign_roles']);
    Route::get('/register-auth', [AuthController::class, 'register_auth']);
    Route::post('/register-auth-handle', [AuthController::class, 'register_auth_handle']);
    Route::get('/delete-user-roles/{id}', [UserController::class, 'delete_user_roles']);
});

//-----role for admin + author (private)
Route::group(['middleware' => 'auth.roles'], function () {
    //category
    Route::get('/add-category', [CategoryController::class, 'add_category']);
    Route::get('/inactive-category/{id}', [CategoryController::class, 'inactive_category']);
    Route::get('/active-category/{id}', [CategoryController::class, 'active_category']);
    Route::post('/save-category', [CategoryController::class, 'save_category']);
    Route::post('/delete-category', [CategoryController::class, 'delete_category']);
    Route::get('/edit-category/{id}', [CategoryController::class, 'edit_category']);
    Route::post('/save-edit-category/{id}', [CategoryController::class, 'save_edit_category']);
    Route::post('/arrange-category', [CategoryController::class, 'arrange_category']);

    //brand
    Route::get('/add-brand', [BrandController::class, 'add_brand']);
    Route::post('/save-brand', [BrandController::class, 'save_brand']);
    Route::get('/inactive-brand/{id}', [BrandController::class, 'inactive_brand']);
    Route::get('/active-brand/{id}', [BrandController::class, 'active_brand']);
    Route::post('/delete-brand', [BrandController::class, 'delete_brand']);
    Route::get('/edit-brand/{id}', [BrandController::class, 'edit_brand']);
    Route::post('/save-edit-brand/{id}', [BrandController::class, 'save_edit_brand']);

    //product
    Route::get('/add-product', [ProductController::class, 'add_product']);
    Route::post('/save-product', [ProductController::class, 'save_product']);
    Route::get('/edit-product/{id}', [ProductController::class, 'edit_product']);
    Route::post('/save-edit-product/{id}', [ProductController::class, 'save_edit_product']);
    Route::post('/change-status-product', [ProductController::class, 'change_status_product']);
    Route::post('/delete-product', [ProductController::class, 'delete_product']);
    Route::post('/add-product-attribute/{id}', [ProductController::class, 'add_product_attribute']);
    Route::post('/delete-product-attribute', [ProductController::class, 'delete_product_attribute']);
    Route::post('/arrange-product', [ProductController::class, 'arrange_product']);

    //product gallery
    Route::post('/reload-product-gallery', [GalleryController::class, 'reload_product_gallery']);
    Route::post('/insert-product-gallery/{id}', [GalleryController::class, 'insert_product_gallery']);
    Route::post('/update-product-gallery-name', [GalleryController::class, 'update_product_gallery_name']);
    Route::post('/delete-product-gallery', [GalleryController::class, 'delete_product_gallery']);

    //account customer
    Route::get('/admin-account-customer', [AdminController::class, 'admin_account_customer']);
    Route::post('/admin-account-customer-change-password', [AdminController::class, 'admin_account_customer_change_password']);
    Route::post('/admin-account-customer-block', [AdminController::class, 'admin_account_customer_block']);

    //faq
    Route::prefix('ad')->group(function () {
        Route::get('add-faq', [FaqController::class, 'add_faq']);
        Route::post('save-faq', [FaqController::class, 'save_faq']);
        Route::get('edit-faq/{id}', [FaqController::class, 'edit_faq']);
        Route::post('save-edit-faq/{id}', [FaqController::class, 'save_edit_faq']);
        Route::post('delete-faq', [FaqController::class, 'delete_faq']);
    });

    //post
    Route::get('/add-category-post', [CategoryPostController::class, 'add_post']);
    Route::post('/save-category-post', [CategoryPostController::class, 'save_post']);
    Route::get('/delete-category-post/{id}', [CategoryPostController::class, 'delete_post']);
    Route::get('/edit-category-post/{id}', [CategoryPostController::class, 'edit_post']);
    Route::get('/change-status-category-post/{id}', [CategoryPostController::class, 'change_status_post']);
    Route::post('/save-edit-category-post/{id}', [CategoryPostController::class, 'save_edit_post']);

    Route::get('/add-post', [PostController::class, 'add_post']);
    Route::post('/save-post', [PostController::class, 'save_post']);
    Route::get('/edit-post/{id}', [PostController::class, 'edit_post']);
    Route::post('/save-edit-post/{id}', [PostController::class, 'save_edit_post']);
    Route::get('/delete-post/{id}', [PostController::class, 'delete_post']);
    Route::post('/add-post-comment', [PostController::class, 'add_post_comment']);
    Route::get('/all-post-comment', [PostController::class, 'all_post_comment']);
    Route::post('/delete-post-comment', [PostController::class, 'delete_post_comment']);

    //coupon
    Route::prefix('ad')->group(function () {
        Route::get('view-coupon', [CouponController::class, 'index']);
        Route::get('add-coupon', [CouponController::class, 'create']);
        Route::get('edit-coupon/{id}', [CouponController::class, 'edit']);
        Route::post('save-coupon', [CouponController::class, 'store']);
        Route::post('save-edit-coupon/{id}', [CouponController::class, 'update']);
        Route::get('delete-coupon/{id}', [CouponController::class, 'destroy']);

        //excel
        Route::post('export-csv', [ProductController::class, 'export_csv']);
        Route::get('statistic-csv', [StatisticController::class, 'export_csv']);

        //home slider
        Route::get('add-home-slider', [HomeSliderController::class, 'create']);
        Route::post('save-home-slider', [HomeSliderController::class, 'store']);
        Route::get('edit-home-slider/{id}', [HomeSliderController::class, 'edit']);
        Route::post('save-edit-home-slider/{id}', [HomeSliderController::class, 'update']);
        Route::get('delete-home-slider/{id}', [HomeSliderController::class, 'destroy']);

        //deal
        Route::get('add-deal', [DealController::class, 'create']);
        Route::post('get-product', [DealController::class, 'get_product']);
        Route::post('save-deal', [DealController::class, 'store']);
        Route::get('edit-deal/{id}', [DealController::class, 'edit']);
        Route::post('save-edit-deal/{id}', [DealController::class, 'update']);
        Route::get('delete-deal/{id}', [DealController::class, 'destroy']);
    });
});
Route::get('/all-post', [PostController::class, 'post']);
Route::get('/category-post', [CategoryPostController::class, 'index']);

//------public admin
//category
Route::get('/list-category', [CategoryController::class, 'list_category']);
Route::post('/search-category', [CategoryController::class, 'search_category']);
Route::get('/list-category-detail/{id}', [CategoryController::class, 'list_category_detail']);

//brand
Route::get('/list-brand', [BrandController::class, 'list_brand']);
Route::post('/search-brand', [BrandController::class, 'search_brand']);
Route::get('/list-brand-detail/{id}', [BrandController::class, 'list_brand_detail']);

//product
Route::get('/list-product', [ProductController::class, 'list_product']);
Route::post('/view-product-content', [ProductController::class, 'view_product_content']);
Route::post('/search-product', [ProductController::class, 'search_product']);
Route::get('/product-attribute/{id}', [ProductController::class, 'product_attribute']);

//product gallery
Route::get('/product-gallery/{id}', [GalleryController::class, 'list_gallery']);

//comment
Route::post('/add-product-comment', [CommentController::class, 'add_comment']);
Route::get('/list-product-comment/{id}', [CommentController::class, 'list_product_comment']);
Route::get('/list-all-product-comment', [CommentController::class, 'all_product_comment']);

//for admin
Route::post('/delete-product-comment', [CommentController::class, 'delete_comment']);
//for customer
Route::post('/delete-product-comment-customer', [CommentController::class, 'delete_comment_customer']);

//statistic
Route::get('/admin-today-list-statistic', [StatisticController::class, 'today_statistic']);
Route::get('/admin-total-list-statistic', [StatisticController::class, 'total_statistic']);

Route::get('/to-date', [StatisticController::class, 'to_date']);

//faq
Route::prefix('ad')->group(function () {
    Route::get('faq', [FaqController::class, 'faq']);
    Route::post('filter-by-date', [StatisticController::class, 'filte_by_date']);
    Route::post('dashboard-chart', [StatisticController::class, 'dashboard_chart']);
    Route::get('home-slider', [HomeSliderController::class, 'index']);
    Route::get('deal', [DealController::class, 'index']);
    Route::post('add-admin-comment', [CommentController::class, 'admin_reply']);
    Route::post('add-question-customer', [FaqController::class, 'add_question']);
    Route::get('list-question', [FaqController::class, 'list_question']);
    Route::post('delete-question', [FaqController::class, 'delete_question']);
});

//admin checkout
Route::get('/admin-checkout', [CheckoutController::class, 'admin_checkout']);
Route::get('/admin-checkout-pending', [CheckoutController::class, 'admin_checkout_pending']);
Route::get('/admin-checkout-success', [CheckoutController::class, 'admin_checkout_success']);
Route::get('/admin-checkout-cancel', [CheckoutController::class, 'admin_checkout_cancel']);
Route::get('/admin-checkout-detail/{id}', [CheckoutController::class, 'admin_checkout_detail']);
Route::post('/admin-confrim-order', [CheckoutController::class, 'admin_confirm_order']);
Route::post('/admin-cancel-order', [CheckoutController::class, 'admin_cancel_order']);
Route::get('/print-pdf-checkout/{checkout_code}', [CheckoutController::class, 'print_pdf_checkout']);
Route::post('/give-message-checkout', [CheckoutController::class, 'give_message']);
Route::post('/update-order-detail/{id}', [CheckoutController::class, 'update_order']);


//-----------------------------------end ADMIN--------------------------------------------------------------//

//-----------------------------------for CUSTOMER---------------------------------------------------//
//cart
Route::get('/shopping-cart', [CartController::class, 'show_cart']);
Route::post('/add-cart', [CartController::class, 'add_cart']);
Route::post('/update-cart', [CartController::class, 'update_cart']);
Route::post('/delete-cart', [CartController::class, 'delete_cart']);
Route::get('/cart-destroy', [CartController::class, 'cart_destroy']);

//customer
Route::get('/register-customer', [CustomerController::class, 'register']);
Route::get('/login-customer', [CustomerController::class, 'login']);
Route::post('/register-customer-handle', [CustomerController::class, 'register_handle']);
Route::post('/login-customer-handle', [CustomerController::class, 'login_handle']);
Route::get('/logout-customer', [CustomerController::class, 'logout']);
Route::get('/forget-password-customer', [CustomerController::class, 'forget_password']);
Route::post('/mail-forget-password-customer', [CustomerController::class, 'mail_forget_password']);
Route::get('/update-new-password', [CustomerController::class, 'update_new_password']);
Route::post('/update-new-password-handle', [CustomerController::class, 'update_new_password_handle']);

//account customer
Route::prefix('u')->group(function () {
    Route::get('account', [CustomerController::class, 'show_information']);
    Route::post('save-change', [CustomerController::class, 'save_information']);
    Route::post('save-change-password', [CustomerController::class, 'save_change_password']);
});

//checkout
Route::get('/checkout', [CheckoutController::class, 'checkout']);
Route::post('/checkout-place-order', [CheckoutController::class, 'place_order']);
Route::post('/cancel-order', [CheckoutController::class, 'cancel_order']);
Route::post('/receive-order', [CheckoutController::class, 'receive_order']);
Route::post('/get-district-data', [DataAddressController::class, 'district']);
Route::post('/get-wards-data', [DataAddressController::class, 'wards']);
//for vnpay callback
Route::get('/checkout-payment', [CheckoutController::class, 'check_payment']);
//blog
Route::prefix('blog')->group(function () {
    Route::get('category/{slug}', [HomeController::class, 'category_post']);
    Route::get('view/{slug}', [HomeController::class, 'view_post']);
    Route::get('search', [PostController::class, 'search']);
});
// -----------------------------------end for CUSTOMER------------------------//

Route::get('/language/{locate}', [LanguageController::class, 'index']);
Route::post('/check-coupon', [CartController::class, 'check_coupon']);
Route::get('/cancel-use-coupon', [CartController::class, 'cancel_coupon']);
Route::get('/tag/{tag}', [HomeController::class, 'tag']);
Route::post('/add-rating', [ProductController::class, 'add_rating']);

Route::get('/login-customer-facebook', [CustomerController::class, 'login_facebook']);
Route::get('/login-customer/callback', [CustomerController::class, 'callback_facebook']);

Route::get('/login-customer-google', [CustomerController::class, 'login_google']);
Route::get('/login-customer/google/callback', [CustomerController::class, 'callback_google']);

//page
Route::get('/page/contact', [PageController::class, 'contact']);
Route::post('/page/contact-save', [PageController::class, 'save_contact']);

Route::post('/uploads-ckeditor', [UploadController::class, 'ckeditor_image']);
Route::get('/file-browser', [UploadController::class, 'ckeditor_browser']);
Route::get('/delete-image-ckeditor', [UploadController::class, 'ckeditor_delete']);
Route::get('/config', [ConfigController::class, 'config']);
Route::get('/api', [ConfigController::class, 'api']);
