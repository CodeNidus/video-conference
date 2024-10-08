<?php


namespace Codenidus\VideoConference\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Codenidus\VideoConference\Http\Resources\UserTokenResource;

class UserTokenController
{
    public function index()
    {
        $user   = Auth::user();
        $options = [];

        if(!request()->secure()) {
            $options['verify'] = false;
        }

        $client = new \GuzzleHttp\Client($options);

        $url = config('video-conference.app_url', 'https://api.vidus.app/api/connected/user-check');
        $username = $user->{config('video-conference.user.username_field', 'email')} ?? null;

        if($username == null) {
            if(config('video-conference.demo_user')) {
                $username = 'demouser@vidus.app';
            } else {
                abort(403, 'The user unique field is invalid.');
            }
        }

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'app-id' => config('video-conference.app_id'),
                    'app-secret' => config('video-conference.app_secret'),
                ],
                'query' => [
                    'username' => $username,
                    'register' => 'true',
                ],
            ]);
        } catch(\Exception $error) {
            abort(500, $error->getMessage());
        }

        $info = json_decode($response->getBody());

        return new UserTokenResource($info);
    }
}