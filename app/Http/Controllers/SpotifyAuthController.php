<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotifyAuthController extends Controller
{
    public function redirectToSpotify()
    {
        $clientId = config('services.spotify.client_id');
        $redirectUri = urlencode(route('spotify.callback'));
        $scopes = 'user-top-read user-read-email';
        $state = csrf_token();
        $url = "https://accounts.spotify.com/authorize?response_type=code&client_id={$clientId}&scope={$scopes}&redirect_uri={$redirectUri}&state={$state}";
        return redirect($url);
    }

    public function handleCallback(Request $request)
    {
        // Aquí deberías intercambiar el code por el token y guardarlo en el usuario
        // ...
        return redirect('/home');
    }
}
