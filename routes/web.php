<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use App\Jobs\sendEmailJob;


$router->group(['prefix' => 'api', 'middleware' => ['verifyToken']], function () use ($router) {
    $router->get('getAllPlanActual', ['uses' => 'PlanActualController@getAllPlanActual']);
    $router->post('insertPlanAndActual', ['uses' => 'PlanActualController@insertPlanAndActual']);
    
});
