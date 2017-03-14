<?php

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



Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/', function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb)
{
    $login_url = $fb->getLoginUrl(['email','user_photos','user_posts']);
    return view('welcome')->with('login_url',$login_url);
});

Route::get('/facebook/callback', function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb)
{
    try {
        $token = $fb->getAccessTokenFromRedirect();
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        dd($e->getMessage());
    }
    if (! $token) {
        $helper = $fb->getRedirectLoginHelper();
        if (! $helper->getError()) {
            abort(403, 'Unauthorized action.');
        }
        dd(
            $helper->getError(),
            $helper->getErrorCode(),
            $helper->getErrorReason(),
            $helper->getErrorDescription()
        );
    }
    $fb->setDefaultAccessToken($token);
    Session::put('fb_user_access_token', (string) $token);
    try {
        $response = $fb->get('/me?fields=id,name,email');
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        dd($e->getMessage());
    }
    $facebook_user = $response->getGraphUser();
    $user = App\User::createOrUpdateGraphNode($facebook_user);
    Auth::login($user,true);
    return redirect('/home');

});
