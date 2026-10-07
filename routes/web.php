<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InitController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\PayementController;
use App\Livewire\Categories\Categories;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GammeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/init-app', [InitController::class, 'init'])->name('init');


Route::middleware(['check.country.cookie'])->group(function () {
    Route::get('/my-skincare-guide', [\App\Http\Controllers\GuideController::class, 'show'])->name('front.guide');
    Route::get('/korean-skincare-tunisie', function () {
        return view('front.pro');
    })->name('pro.page');
    Route::get('/blogs', [\App\Http\Controllers\BlogController::class, 'list'])->name('front.blogs.index');
    Route::get('/blogs/{blog}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blogs.show');

    Route::get('/appointment', function () {
        return view('front.appointment');
    })->name('appointment');

    Route::post('/appointments', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/check/{date}', [\App\Http\Controllers\AppointmentController::class, 'check'])
        ->name('appointments.check');

    Route::get('/search/suggestions', [FrontController::class, 'suggestions'])->name('search.suggestions');

    Route::get('/view_produit/{id}', [HomeController::class, 'view_produit']);
    Route::get('/set-cookie/{pays}', [HomeController::class, 'set_cookie']);
    Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
    Route::get('/', [FrontController::class, 'index'])->name('home');
    Route::get('/login', [FrontController::class, 'login'])->name('login');
    Route::get('/login', [FrontController::class, 'login'])->name('login');
    Route::get('/shop', [FrontController::class, 'shop'])->name('shop');
    Route::post('/shop/ajax', [FrontController::class, 'shop_ajax'])->name('shop_ajax');
    Route::post('/comments', [FrontController::class, 'addComment'])->name('comments.store')->middleware('auth');
    Route::get('/category', [FrontController::class, 'category'])->name('category');
    Route::get('/category', [FrontController::class, 'category'])->name('category');
    Route::get('/produit/{id}', [FrontController::class, 'produit'])->name('produit');
    Route::get('/contact', [FrontController::class, 'contact'])->name('contact');
    Route::post('/contact', [FrontController::class, 'contact_post'])->name('contact_post');
    Route::get('/register', [FrontController::class, 'register'])->name('register');
    Route::get('/password.reset/token', [FrontController::class, 'password_reset'])->name('password.reset');
    Route::get('/forgotpassword', [FrontController::class, 'forgotpassword'])->name('forgotpassword');
    Route::get('/print/commande/{id}', [HomeController::class, 'print_commande'])->name('print_commande');
    Route::get('/print/commande2/{token}', [HomeController::class, 'print_commande2'])->name('print_commande2');
    Route::get('/print_bordereau', [HomeController::class, 'print_bordereau'])->name('print_bordereau');
    Route::post('/client/ajouter_au_panier', [PanierController::class, 'add']);
    Route::get('/client/json/cart', [PanierController::class, 'count_panier']);
    Route::post('/client/ajouter_favoris', [FavorisController::class, 'add']);
    Route::post('/client/preorder', [PanierController::class, 'preorder']);

    Route::get('/checkout', [FrontController::class, 'checkout'])->name('checkout');
    Route::post('/checkout-with-points', [FrontController::class, 'checkoutWithPoints'])->name('checkout.with.points');
    Route::post('/checkout/mixed', [FrontController::class, 'checkoutMixed'])
        ->name('checkout.mixed')
        ->middleware('auth');
    Route::get('/faqs', [FrontController::class, 'faqs'])->name('faqs');
    Route::get('/terms', [FrontController::class, 'terms'])->name('terms');
    Route::get('/return-policy', [FrontController::class, 'return_policy'])->name('return-policy');
    Route::get('/error-page', [FrontController::class, 'error_page'])->name('error-page');
    Route::get('/register-send', [FrontController::class, 'register_send'])->name('register-send');


    //Gestion des panier
    Route::get('/cart', [FrontController::class, 'cart'])->name('cart');
    Route::get('/cart/clear-cart', [PanierController::class, 'clearPanier'])->name('clear-cart');
    Route::get('/panier/delete/{produit_id}', [PanierController::class, 'deleteElement'])->name('panier.delete');
    Route::get('/panier/update/{produit_id}/{quantite}', [PanierController::class, 'updateQuantite'])->name('panier.update');




    Route::get('/payment-success/{token}', [PayementController::class, 'payment_success'])->name('payment-success');
    Route::get('/payment-failure', [PayementController::class, 'payment_failure'])->name('payment-failure');
    Route::post('/commander', [PayementController::class, 'commander'])
        ->middleware(\App\Http\Middleware\PreventDuplicateCheckout::class)->name('commander');
    Route::post('/commander_payment', [PayementController::class, 'commander_payment'])
        ->middleware(\App\Http\Middleware\PreventDuplicateCheckout::class)->name('commander_payment');

    // Route du client
    Route::middleware(['auth'])->group(function () {
        Route::get('/change-password', [FrontController::class, 'change_password'])->name('change_password');
        Route::get('/favoris', [FrontController::class, 'favoris'])->name('favoris');
        Route::get('/profile', [FrontController::class, 'profile'])->name('profile');
        Route::get('/orders', [FrontController::class, 'orders'])->name('orders');
    });

    Route::get('/after/commandes', [FrontController::class, 'after_commande'])->name('after_commande');
});




Route::middleware(['auth'])->group(function () {
    Route::get('/guides/create', [\App\Http\Controllers\GuideController::class, 'create'])
        ->name('guides.create');

    Route::get('/guides/{guide}/edit', [\App\Http\Controllers\GuideController::class, 'edit'])->name('guides.edit');

    Route::post('/guides', [\App\Http\Controllers\GuideController::class, 'store'])->name('guides.store');
    Route::put('/guides/{guide}', [\App\Http\Controllers\GuideController::class, 'update'])->name('guides.update');

    Route::get('/open_url_notification', [AdminController::class, 'open_url_notification'])
        ->name('open_url_notification');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');
    Route::post('/dashboard/filtre', [AdminController::class, 'dashboard'])
        ->name('filtre-dashboard');

    Route::get('/admin/produits', [AdminController::class, 'produits'])
        ->name('produits')
        ->middleware('permission:product_view');

    Route::get('/admin/corbeille', [AdminController::class, 'corbeille'])->name('corbeille');
    Route::get('/admin/produit/{id}/update', [AdminController::class, 'produits_update'])
        ->name('produits.update')
        ->middleware('permission:product_edit');

    Route::get('/admin/produit/{id}/historique', [AdminController::class, 'historique'])
        ->name('produits.historique')
        ->middleware('role:admin');
    Route::get('/admin/comments', function () {
        return view('admin.comments.liste'); // chemin blade : resources/views/admin/comments/list.blade.php
    })->name('admin.comments.list')
        ->middleware('role:admin');


    Route::get('/admin/produit/add', [AdminController::class, 'produit_add'])
        ->name('produit.add')
        ->middleware('permission:product_add');

    Route::get('/admin/commandes', [AdminController::class, 'commandes'])
        ->name('commandes')
        ->middleware('permission:order_view');
    Route::get('/admin/parametres', [AdminController::class, 'parametres'])
        ->name('parametres');

    Route::get('/admin/personnels', [AdminController::class, 'personnels'])
        ->name('personnels')
        ->middleware('role:admin');
    Route::get('/admin/promotions', [AdminController::class, 'promotions'])
        ->name('promotions');
    Route::get('/admin/promotions/{id}', [AdminController::class, 'promotions'])
        ->name('promotions_produit');
    Route::get('/admin/commande/{id}', [AdminController::class, 'details_commande'])
        ->name('details_commande');
    Route::get('clients', [AdminController::class, 'clients'])
        ->name('clients')
        ->middleware('permission:clients_view');
    Route::get('/admin/export/clients', [AdminController::class, 'export_clients'])
        ->name('export_clients')
        ->middleware('permission:clients_view');

    Route::get('contact-admin', [AdminController::class, 'contact_admin'])
        ->name('contact-admin')
        ->middleware('permission:setting_view');

    Route::post('contact-admin.post', [AdminController::class, 'contact_admin_update'])
        ->name('contact-admin.post')
        ->middleware('permission:setting_view');


    Route::get('/admin/get_live_notifications', [AdminController::class, 'live_notifications'])
        ->name('live_notifications');

    Route::post('/update-config', [AdminController::class, 'update_config'])
        ->name('update-config');

    Route::get('admin/new_commande', [AdminController::class, 'new_commande'])
        ->name('new_commande')
        ->middleware('permission:order_add');

    Route::post('admin/add_note', [AdminController::class, 'add_note'])
        ->name('add_note')
        ->middleware('permission:order_edit');


    Route::get('/admin/commande/{id}/edit_commande', [AdminController::class, 'edit_commande'])
        ->name('edit_commande')
        ->middleware('permission:order_edit');

    Route::get('/admin/personnel/delete/{id}', [AdminController::class, 'delete_personnel'])
        ->name('delete_personnel')
        ->middleware('role:admin');

    Route::post('/admin/update-personnel-permissions', [AdminController::class, 'update_permission'])
        ->name('update-personnel-permissions')
        ->middleware('role:admin');


    Route::get('/admin/delete-all-notifications', [AdminController::class, 'delete_all_notifications'])
        ->name('delete-all-notifications');


    //gestion des categories
    Route::get('/admin/categories', [CategoriesController::class, 'categories'])
        ->name('categories');
    Route::get('/admin/souscategories', [CategoriesController::class, 'souscategories'])
        ->name('souscategories');
    Route::get('/admin/categories.reorder', [CategoriesController::class, 'reorder'])
        ->name('categories.reorder');

    Route::get('/admin/souscategories.reorder', [CategoriesController::class, 'sous_reorder'])
        ->name('souscategories.reorder');

    Route::get('/admin/soussouscategories', [CategoriesController::class, 'soussouscategories'])
        ->name('soussouscategories');

// Réorganisation (drag & drop) des sous-sous-catégories
    Route::get('/admin/soussouscategories.reorder', [CategoriesController::class, 'soussous_reorder'])
        ->name('soussouscategories.reorder');

    Route::get('/admin/contacts/clients', [AdminController::class, 'contacts'])
        ->name('contacts-list');
    //gestion des marques
    Route::get('/admin/marques', [CategoriesController::class, 'marques'])
        ->name('marques');

    //gestion des infos de la home
    Route::get('/admin/home_infos', [AdminController::class, 'home_infos'])
        ->name('home_infos');
    Route::post('/admin/update_home_infos', [AdminController::class, 'update_home_infos'])
        ->name('update_home_infos');

    //gestion des coupons
    Route::resource('coupons', CouponController::class);
    Route::post('/produits/reorder', [AdminController::class, 'produit_reorder'])->name('produits.reorder');
    Route::resource('banners', BannerController::class);
    Route::get('/admin/banners.reorder', [BannerController::class, 'reorder'])
        ->name('banners.reorder');

    Route::prefix('admin')->group(function () {

        // 🔹 Liste des gammes
        Route::get('/gammes', [GammeController::class, 'index'])->name('gammes.index');

        // 🔹 Réorganisation (drag & drop SortableJS)
        Route::get('/gammes/reorder', [GammeController::class, 'reorder'])->name('gammes.reorder');
    });
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('blogs', \App\Http\Controllers\BlogController::class);

        Route::get('/appointments/calendar', [\App\Http\Controllers\AppointmentController::class, 'getAppointments'])
            ->name('appointments.calendar');

        Route::resource('appointments', \App\Http\Controllers\AppointmentController::class)->only(['index', 'destroy', 'show']);;
        Route::get('/appointments-json', [App\Http\Controllers\AppointmentController::class, 'getAppointments'])->name('appointments.json');
        Route::patch('/appointments/{appointment}/confirm', [\App\Http\Controllers\AppointmentController::class, 'confirm'])
            ->name('appointments.confirm');
    });
    });
