<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AfolakeController;
use App\Http\Controllers\WelcomeController;

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


// Create
// Read
// Update
// Delete

// GET
// PATCH/PUT
// POST
// DELETE

// Intent        | Action Verb | URI       | Controller Action | CRUD Operation
// Create a user | POST        | /users    | store             | Create
// Get all users | GET         | /users    | index             | Read
// Get a user    | GET         | /users/1  | show              | Read
// Update a user | PUT         | /users/1  | update            | Update
// Delete a user | DELETE      | /users/1  | destroy           | Delete


Route::get('/', function () {
   // return view('welcome');
   return 'Hello Laravel';
});

Route::get('afolake', [AfolakeController::class, 'index']);

// Create a route called `welcome` and display the message
// `Welcome to my web app` using a controller when the
// route is visited.
//Read the routing documentation
//5 projects on Monday



Route::get('welcome', [WelcomeController::class, 'index']);
