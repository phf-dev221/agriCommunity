<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SousCategorieController;
use App\Http\Controllers\SousCategoryController;

Route::prefix('v1')->group(function () {
    // Authentification
    Route::post('/register', [ApiController::class, 'register']);
    Route::post('/login', [ApiController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [ApiController::class, 'logout']);
        Route::get('/user', [ApiController::class, 'user']);
    });

    // Tenants (superadmin)
    // Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    //     Route::apiResource('tenants', TenantController::class);
    // });

    // Catégories et sous-catégories (superadmin)
    Route::middleware(['auth-api', 'role:superadmin'])->group(function () {
        Route::apiResource('categories', CategorieController::class);
        Route::apiResource('sous-categories', SousCategorieController::class);
    });

    // Produits (agriculteurs)
    Route::middleware(['auth-api', 'role:agriculteur', 'tenant'])->group(function () {
        Route::apiResource('products', ProductController::class);
    });

    // // Paniers (acheteurs)
    // Route::middleware(['auth:api', 'role:acheteur', 'tenant'])->group(function () {
    //     Route::get('cart', [CartController::class, 'index']);
    //     Route::post('cart/add', [CartController::class, 'addToCart']);
    //     Route::put('cart/update/{productId}', [CartController::class, 'updateCart']);
    //     Route::delete('cart/remove/{productId}', [CartController::class, 'removeFromCart']);
    //     Route::delete('cart/clear', [CartController::class, 'clearCart']);
    // });

    // // Commandes (acheteurs pour création, agriculteurs pour suivi)
    // Route::middleware(['auth:api', 'tenant'])->group(function () {
    //     Route::post('orders', [OrderController::class, 'store'])->middleware('role:acheteur');
    //     Route::get('orders', [OrderController::class, 'index']);
    //     Route::get('orders/{order}', [OrderController::class, 'show']);
    //     Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('role:agriculteur');
    // });

    // // Plans (superadmin pour CRUD, tous pour lecture)
    // Route::middleware('auth:api')->group(function () {
    //     Route::get('plans', [PlanController::class, 'index']);
    //     Route::get('plans/{plan}', [PlanController::class, 'show']);
    // });
    // Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    //     Route::post('plans', [PlanController::class, 'store']);
    //     Route::put('plans/{plan}', [PlanController::class, 'update']);
    //     Route::delete('plans/{plan}', [PlanController::class, 'destroy']);
    // });

    // // Abonnements (tenants pour souscription, superadmin pour gestion)
    // Route::middleware(['auth:api', 'tenant'])->group(function () {
    //     Route::get('subscriptions', [SubscriptionController::class, 'index']);
    //     Route::post('subscriptions', [SubscriptionController::class, 'subscribe'])->middleware('role:agriculteur');
    // });
    // Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    //     Route::apiResource('subscriptions', SubscriptionController::class)->except(['index', 'store']);
    // });

    // // Passerelles de paiement (superadmin)
    // Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    //     Route::apiResource('payment-gateways', PaymentGatewayController::class);
    // });

    // // Commissions (superadmin)
    // Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    //     Route::apiResource('commissions', CommissionController::class);
    // });

    // // Paiements (acheteurs pour création, superadmin pour gestion)
    // Route::middleware(['auth:api', 'tenant'])->group(function () {
    //     Route::post('payments', [PaymentController::class, 'store'])->middleware('role:acheteur');
    //     Route::get('payments', [PaymentController::class, 'index']);
    // });
    // Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    //     Route::apiResource('payments', PaymentController::class)->except(['index', 'store']);
    // });

    // // Factures (acheteurs/agriculteurs pour lecture, superadmin pour gestion)
    // Route::middleware(['auth:api', 'tenant'])->group(function () {
    //     Route::get('invoices', [InvoiceController::class, 'index']);
    //     Route::get('invoices/{invoice}', [InvoiceController::class, 'show']);
    // });
    // Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    //     Route::apiResource('invoices', InvoiceController::class)->except(['index', 'show']);
    // });

    // // Coupons (agriculteurs pour CRUD, acheteurs pour lecture)
    // Route::middleware(['auth:api', 'tenant'])->group(function () {
    //     Route::get('coupons', [CouponController::class, 'index']);
    //     Route::get('coupons/{coupon}', [CouponController::class, 'show']);
    //     Route::post('coupons', [CouponController::class, 'store'])->middleware('role:agriculteur');
    //     Route::put('coupons/{coupon}', [CouponController::class, 'update'])->middleware('role:agriculteur');
    //     Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->middleware('role:agriculteur');
    // });

    // // Réductions (agriculteurs)
    // Route::middleware(['auth:api', 'role:agriculteur', 'tenant'])->group(function () {
    //     Route::apiResource('discounts', DiscountController::class);
    // });

    // // Annonces (agriculteurs pour leurs tenants, superadmin pour globales)
    // Route::middleware(['auth:api', 'tenant'])->group(function () {
    //     Route::get('announcements', [AnnouncementController::class, 'index']);
    //     Route::get('announcements/{announcement}', [AnnouncementController::class, 'show']);
    //     Route::post('announcements', [AnnouncementController::class, 'store'])->middleware('role:agriculteur|superadmin');
    //     Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])->middleware('role:agriculteur|superadmin');
    //     Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->middleware('role:agriculteur|superadmin');
    // });

    // // Notifications (tous pour lecture, superadmin pour gestion)
    // Route::middleware(['auth:api'])->group(function () {
    //     Route::get('notifications', [NotificationController::class, 'index']);
    //     Route::get('notifications/{notification}', [NotificationController::class, 'show']);
    //     Route::put('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    // });
    // Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    //     Route::post('notifications', [NotificationController::class, 'store']);
    //     Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);
    // });
});