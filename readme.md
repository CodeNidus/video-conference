
# Video Conference

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

CodeNidus video conference laravel/vue package

## Installation

Via Composer

``` bash
composer require codenidus/video-conference
```
Install Laravel package

``` bash
php artisan videoconference:install
```

Install NPM packages
``` bash
npm install cnidus-videoconference-vue
```

## Usage

#### Set laravel project .env variables
```
# WEBRTC CONFIGS  
MIX_WEBRTC_TOKEN_URL="/api/videoconference/userToken"  
MIX_WEBRTC_THEME="Default"

VIDEOCONFERENCE_APP_ID="Project id"
VIDEOCONFERENCE_APP_SECRET="App Secret Token"
```

#### Add vue loader to webpack
Editing the webpack.mix.js config file and add this lines for load vue and mp3 files
```
 mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.mp3$/,
        use: [
          {
            loader: 'file-loader'
          }
        ],
      },
    ],
  },
});

mix.js('resources/js/app.js', 'public/js').vue()
```

#### Modify in vue project 
Editing the app.js file and add this lines for provide webrtc to project children components
```
import { VideoConferenceCreator } from "cnidus-videoconference-vue"

const videoconference = VideoConferenceCreator()

app.use(videoconference)
```
Add webrtc vue components in project
```
<VCRooms />

<VCRoomJoin room-id="" />
```

Store user login token in local storage on specific key name
```
localStorage.setItem('cnidus.videoconference.laravel.token', 'user-token')
```


## Demo

[https://www.codenidus.com/projects/video-meeting](https://codenidus.com/projects/video-meeting)

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email codenidus@gmail.com instead of using the issue tracker.

## Credits

- [CodeNidus](https://www.codenidus.com)

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/codenidus/video-conference.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/codenidus/video-conference.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/codenidus/video-conference
[link-downloads]: https://packagist.org/packages/codenidus/video-conference

[link-author]: https://github.com/codenidus
