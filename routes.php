<?php

use App\Controllers\DashboardController;
use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\RegistrarController;
use Core\Route;

(new Route())
    ->get('/', IndexController::class)
    ->get('/login', [LoginController::class, 'index'])
    ->post('/login', [LoginController::class, 'login'])
    ->get('/logout', LogoutController::class)
    ->get('/registrar', [RegistrarController::class, 'index'])
    ->post('/registrar', [RegistrarController::class, 'store'])
    ->get('/dashboard', DashboardController::class)
    ->post('/notas', [DashboardController::class, 'store'])
    ->post('/notas/atualizar', [DashboardController::class, 'update'])
    ->post('/notas/deletar', [DashboardController::class, 'destroy'])
    ->run();
