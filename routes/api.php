<?php

use App\Http\Controllers\TimeWallController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/postback/timewall', [TimeWallController::class, 'postback']);
Route::match(['get', 'post'], '/postback/monlix', [App\Http\Controllers\MonlixController::class, 'postback']);

// ─── Universal Dynamic Postback (handles ALL offerwalls by slug) ────────────
Route::match(['get', 'post'], '/postback/{slug}', [App\Http\Controllers\OfferwallController::class, 'postback'])
    ->middleware('throttle:120,1')  // 120 req/min — generous for high-volume networks
    ->where('slug', '[a-z0-9\-]+');

Route::get('/user', function (Request $request) {
    return $request->user()->only(['id', 'name', 'email', 'points', 'balance_bdt', 'created_at']);
})->middleware('auth:sanctum');

// Centralized stateless Blog API routes (with CORS preflight support)
Route::match(['get', 'options'], '/blog-posts/latest', [App\Http\Controllers\Api\BlogPostApiController::class, 'latest'])
    ->middleware('throttle:60,1');  // 60 req/min — abuse protection for blog subdomains
Route::match(['get', 'options'], '/blog-posts/{id}', [App\Http\Controllers\Api\BlogPostApiController::class, 'show'])
    ->middleware('throttle:60,1');
