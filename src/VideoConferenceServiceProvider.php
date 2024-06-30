<?php

namespace Codenidus\VideoConference;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Http\Kernel;
use Codenidus\VideoConference\Http\Middleware\BaseVideoConferenceAuthorize;


class VideoConferenceServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Kernel $kernel): void
    {
        // set middleware in router
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('videoconferenceAuthorize', BaseVideoConferenceAuthorize::class);

        $this->registerRoutes();
        $this->registerResources();
        $this->registerVueAssetsPublish();
        $this->registerConfigFilePublish();
        $this->registerMiddlewareFilePublish();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'videoconference');
    }

    public function registerVueAssetsPublish()
    {
        $this->publishes([
            __DIR__.'/../resources/js/app-video-conference.js' => resource_path('js/app-video-conference.js'),
            __DIR__.'/../resources/js/App-video-conference.vue' => resource_path('js/App-video-conference.vue'),
            __DIR__.'/../resources/js/router/router-video-conference.js' => resource_path('js/router/router-video-conference.js'),
            __DIR__.'/../resources/js/router/webrtc.js' => resource_path('js/router/webrtc.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/audio/admit.mp3' => resource_path('js/plugins/Webrtc/assets/audio/admit.mp3'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/audio/beep.mp3' => resource_path('js/plugins/Webrtc/assets/audio/beep.mp3'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.eot' => resource_path('js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.eot'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.rrf' => resource_path('js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.rrf'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.svg' => resource_path('js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.svg'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.ttf' => resource_path('js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.ttf'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.woff' => resource_path('js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.woff'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.woff2' => resource_path('js/plugins/Webrtc/assets/fonts/Roboto/roboto-light.woff2'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/images/face-profile.jpg' => resource_path('js/plugins/Webrtc/assets/images/face-profile.jpg'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/images/medal.png' => resource_path('js/plugins/Webrtc/assets/images/medal.png'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/images/pirate-hat.webp' => resource_path('js/plugins/Webrtc/assets/images/pirate-hat.webp'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/scss/DefaultThemeStyle.scss' => resource_path('js/plugins/Webrtc/assets/scss/DefaultThemeStyle.scss'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/Rooms.vue' => resource_path('js/plugins/Webrtc/components/Rooms.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/RoomCreate.vue' => resource_path('js/plugins/Webrtc/components/RoomCreate.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/RoomsList.vue' => resource_path('js/plugins/Webrtc/components/RoomsList.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/RoomJoin.vue' => resource_path('js/plugins/Webrtc/components/RoomJoin.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/VideoConference.vue' => resource_path('js/plugins/Webrtc/components/VideoConference.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/actions/AdmitAction.vue' => resource_path('js/plugins/Webrtc/components/actions/AdmitAction.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/actions/BanAction.vue' => resource_path('js/plugins/Webrtc/components/actions/BanAction.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/actions/CanvasTextAction.vue' => resource_path('js/plugins/Webrtc/components/actions/CanvasTextAction.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/actions/FaceApiAction.vue' => resource_path('js/plugins/Webrtc/components/actions/FaceApiAction.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/actions/MultiAction.vue' => resource_path('js/plugins/Webrtc/components/actions/MultiAction.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/actions/MuteUserMicAction.vue' => resource_path('js/plugins/Webrtc/components/actions/MuteUserMicAction.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/actions/TerminateAction.vue' => resource_path('js/plugins/Webrtc/components/actions/TerminateAction.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/themes/CanvasfaceVideoConference.vue' => resource_path('js/plugins/Webrtc/components/themes/CanvasfaceVideoConference.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/themes/DefaultVideoConference.vue' => resource_path('js/plugins/Webrtc/components/themes/DefaultVideoConference.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/modules/ChatModule.vue' => resource_path('js/plugins/Webrtc/components/modules/ChatModule.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/modules/PeopleModule.vue' => resource_path('js/plugins/Webrtc/components/modules/PeopleModule.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/modules/CommandsDeckModule.vue' => resource_path('js/plugins/Webrtc/components/modules/CommandsDeckModule.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/components/modules/ShareScreenModule.vue' => resource_path('js/plugins/Webrtc/components/modules/ShareScreenModule.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/configs/index.js' => resource_path('js/plugins/Webrtc/configs/index.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/actions/admitAction.js' => resource_path('js/plugins/Webrtc/actions/admitAction.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/actions/banAction.js' => resource_path('js/plugins/Webrtc/actions/banAction.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/actions/chatAction.js' => resource_path('js/plugins/Webrtc/actions/chatAction.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/actions/canvasTextAction.js' => resource_path('js/plugins/Webrtc/actions/canvasTextAction.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/actions/faceApiAction.js' => resource_path('js/plugins/Webrtc/actions/faceApiAction.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/actions/muteUserMicAction.js' => resource_path('js/plugins/Webrtc/actions/muteUserMicAction.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/actions/terminateAction.js' => resource_path('js/plugins/Webrtc/actions/terminateAction.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/modules/Events.js' => resource_path('js/plugins/Webrtc/modules/Events.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/modules/Media.js' => resource_path('js/plugins/Webrtc/modules/Media.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/modules/People.js' => resource_path('js/plugins/Webrtc/modules/People.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/modules/Room.js' => resource_path('js/plugins/Webrtc/modules/Room.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/modules/MediapipeBodySegment.js' => resource_path('js/plugins/Webrtc/modules/MediapipeBodySegment.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/modules/MediapipeFaceDetect.js' => resource_path('js/plugins/Webrtc/modules/MediapipeFaceDetect.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/modules/ShareScreen.js' => resource_path('js/plugins/Webrtc/modules/ShareScreen.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/Axios.js' => resource_path('js/plugins/Webrtc/Axios.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/PeerJs.js' => resource_path('js/plugins/Webrtc/PeerJs.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/Socket.js' => resource_path('js/plugins/Webrtc/Socket.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/WebRTC.js' => resource_path('js/plugins/Webrtc/WebRTC.js'),
            __DIR__.'/../resources/js/plugins/Webrtc/helper.vue' => resource_path('js/plugins/Webrtc/helper.vue'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/images/medal.png' => public_path('images/medal.png'),
            __DIR__.'/../resources/js/plugins/Webrtc/assets/images/pirate-hat.webp' => public_path('images/pirate-hat.webp'),
        ], 'videoconference-vue-force');

        $this->publishes([
            __DIR__.'/../resources/js/plugins/Webrtc/components/VideoConferenceActions.vue' => resource_path('js/plugins/Webrtc/components/VideoConferenceActions.vue'),
        ], 'videoconference-vue');

        $this->publishes([
            __DIR__.'/../resources/models/face/face_detection_full.binarypb' => public_path('models/face/face_detection_full.binarypb'),
            __DIR__.'/../resources/models/face/face_detection_full_range.tflite' => public_path('models/face/face_detection_full_range.tflite'),
            __DIR__.'/../resources/models/face/face_detection_full_range_sparse.tflite' => public_path('models/face/face_detection_full_range_sparse.tflite'),
            __DIR__.'/../resources/models/face/face_detection_short.binarypb' => public_path('models/face/face_detection_short.binarypb'),
            __DIR__.'/../resources/models/face/face_detection_short_range.tflite' => public_path('models/face/face_detection_short_range.tflite'),
            __DIR__.'/../resources/models/face/face_detection_solution_simd_wasm_bin.data' => public_path('models/face/face_detection_solution_simd_wasm_bin.data'),
            __DIR__.'/../resources/models/face/face_detection_solution_simd_wasm_bin.js' => public_path('models/face/face_detection_solution_simd_wasm_bin.js'),
            __DIR__.'/../resources/models/face/face_detection_solution_simd_wasm_bin.wasm' => public_path('models/face/face_detection_solution_simd_wasm_bin.wasm'),
            __DIR__.'/../resources/models/face/face_detection_solution_wasm_bin.js' => public_path('models/face/face_detection_solution_wasm_bin.js'),
            __DIR__.'/../resources/models/face/face_detection_solution_wasm_bin.wasm' => public_path('models/face/face_detection_solution_wasm_bin.wasm'),
            __DIR__.'/../resources/models/face/face_expression_model-shard1' => public_path('models/face/face_expression_model-shard1'),
            __DIR__.'/../resources/models/face/face_expression_model-weights_manifest.json' => public_path('models/face/face_expression_model-weights_manifest.json'),
            __DIR__.'/../resources/models/face/face_landmark_68_model-shard1' => public_path('models/face/face_landmark_68_model-shard1'),
            __DIR__.'/../resources/models/face/face_landmark_68_model-weights_manifest.json' => public_path('models/face/face_landmark_68_model-weights_manifest.json'),
            __DIR__.'/../resources/models/face/face_landmark_68_tiny_model-shard1' => public_path('models/face/face_landmark_68_tiny_model-shard1'),
            __DIR__.'/../resources/models/face/face_landmark_68_tiny_model-weights_manifest.json' => public_path('models/face/face_landmark_68_tiny_model-weights_manifest.json'),
            __DIR__.'/../resources/models/face/face_recognition_model-shard1' => public_path('models/face/face_recognition_model-shard1'),
            __DIR__.'/../resources/models/face/face_recognition_model-shard2' => public_path('models/face/face_recognition_model-shard2'),
            __DIR__.'/../resources/models/face/face_recognition_model-weights_manifest.json' => public_path('models/face/face_recognition_model-weights_manifest.json'),
            __DIR__.'/../resources/models/face/tiny_face_detector_model-shard1' => public_path('models/face/tiny_face_detector_model-shard1'),
            __DIR__.'/../resources/models/face/tiny_face_detector_model-weights_manifest.json' => public_path('models/face/tiny_face_detector_model-weights_manifest.json'),
            __DIR__.'/../resources/models/selfie/selfie_segmentation.binarypb' => public_path('models/selfie/selfie_segmentation.binarypb'),
            __DIR__.'/../resources/models/selfie/selfie_segmentation.tflite' => public_path('models/selfie/selfie_segmentation.tflite'),
            __DIR__.'/../resources/models/selfie/selfie_segmentation_solution_simd_wasm_bin.js' => public_path('models/selfie/selfie_segmentation_solution_simd_wasm_bin.js'),
            __DIR__.'/../resources/models/selfie/selfie_segmentation_solution_simd_wasm_bin.wasm' => public_path('models/selfie/selfie_segmentation_solution_simd_wasm_bin.wasm'),
        ], 'mediapipe-models');
    }

    public function registerConfigFilePublish()
    {
      $this->publishes([
        __DIR__.'/../config/video-conference.php' => config_path('video-conference.php'),
      ], 'videoconference-config');
    }

    public function registerMiddlewareFilePublish()
    {
        $this->publishes([
            __DIR__.'/Http/Middleware/VideoConferenceAuthorize.php' => app_path('Http/Middleware/VideoConferenceAuthorize.php'),
        ], 'videoconference-middleware');
    }

    public function registerRoutes()
    {
        Route::group($this->webRouteConfiguration(), function () {
           $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });


        Route::group($this->apiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/video-conference.php', 'video-conference');

        $this->commands([
            Console\ProcessCommand::class
        ]);

        // Register the service the package provides.
        $this->app->singleton('video-conference', function ($app) {
            return new VideoConference;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['video-conference'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    protected function webRouteConfiguration()
    {
        $route = [
            'prefix' => config('video-conference.prefix'),
            'middleware' => []
        ];

        $middlewareList = array_merge(['web'],  config('video-conference.routes.web.middleware', []));

        if(!config('video-conference.demo_user')) {
            $route['middleware'] = array_merge($route['middleware'], $middlewareList);
        }

        $route['middleware'][] = 'videoconferenceAuthorize';

        return $route;
    }

    protected function apiRouteConfiguration()
    {
        $route = [
            'prefix' => 'api/'. config('video-conference.prefix'),
            'middleware' => []
        ];

        $middlewareList = array_merge(['api'],  config('video-conference.routes.api.middleware', []));

        if(!config('video-conference.demo_user')) {
            $route['middleware'] = array_merge($route['middleware'], $middlewareList);
        }

        $route['middleware'][] = 'videoconferenceAuthorize';

        return $route;
    }
}
