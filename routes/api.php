<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->group(['middleware' => 'cors'], function ($router) {
        $router->group(['middleware' => 'jwt.auth'], function ($router) {
            $router->get('posts', function () {
                $posts = [
                    [
                        'id' => 1,
                        'title' => 'Post 1 - Teste 1',
                        'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum at venenatis sapien.'
                    ],
                    [
                        'id' => 2,
                        'title' => 'Post 2',
                        'body' => 'Nullam ultrices dui porttitor libero faucibus luctus.'
                    ],
                    [
                        'id' => 3,
                        'title' => 'Post 3',
                        'body' => 'Duis aliquet, nibh vel accumsan mattis, orci dui lobortis ligula, in ultrices velit libero ut justo.'
                    ],
                ];
                return response()->json(compact('posts'));
            });
        });

        //obter token
        $router->post('login', 'Api\AuthController@login');
        //para atualizar o token
        $router->post('refresh_token', function(){
            try {
                $token = JWTAuth::parseToken()->refresh();
                //return response()->json(compact('token'));
                return response()->json(['success' => true, 'data'=> [ 'token' => $token ]]);
            }catch (JWTException $exception){
                return response()->json(['error' => 'token_invalid'],400);
            }
        });
    });
});
