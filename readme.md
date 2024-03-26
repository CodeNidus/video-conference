
# Video Conference

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

CodeNidus video conference laravel/vue package

## Installation

Via Composer

``` bash
$ composer require codenidus/video-conference
```
Install Laravel package

``` bash
$ php artisan videoconference:install
```

Install NPM packages
``` bash
$ npm install vue vue-loader axios peerjs socket.io-client@^4.1.2
$ npm install @mediapipe/face_detection @mediapipe/selfie_segmentation
$ npm install @tensorflow-models/body-segmentation @tensorflow-models/face-detection @tensorflow/tfjs-backend-webgl @tensorflow/tfjs-converter @tensorflow/tfjs-core
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

#### Modify in vue project 
Editing the app.js file and add this lines for provide webrtc to project children components
```
import WebRTC from "./utils/Webrtc/WebRTC.js";
app.provide('webrtc', WebRTC)
```
Add webrtc vue components in project
```
import Rooms from "@/components/webrtc/Rooms.vue";
```
Webrtc routes for vue-router
```
import webrtcRoutes from "@/router/webrtc.js";
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
