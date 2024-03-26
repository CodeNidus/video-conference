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
            __DIR__.'/../resources/js/assets/webrtc/audio/admit.mp3' => resource_path('js/assets/webrtc/audio/admit.mp3'),
            __DIR__.'/../resources/js/assets/webrtc/audio/beep.mp3' => resource_path('js/assets/webrtc/audio/beep.mp3'),
            __DIR__.'/../resources/js/assets/webrtc/fonts/Roboto/roboto-light.eot' => resource_path('js/assets/webrtc/fonts/Roboto/roboto-light.eot'),
            __DIR__.'/../resources/js/assets/webrtc/fonts/Roboto/roboto-light.rrf' => resource_path('js/assets/webrtc/fonts/Roboto/roboto-light.rrf'),
            __DIR__.'/../resources/js/assets/webrtc/fonts/Roboto/roboto-light.svg' => resource_path('js/assets/webrtc/fonts/Roboto/roboto-light.svg'),
            __DIR__.'/../resources/js/assets/webrtc/fonts/Roboto/roboto-light.ttf' => resource_path('js/assets/webrtc/fonts/Roboto/roboto-light.rrf'),
            __DIR__.'/../resources/js/assets/webrtc/fonts/Roboto/roboto-light.woff' => resource_path('js/assets/webrtc/fonts/Roboto/roboto-light.woff'),
            __DIR__.'/../resources/js/assets/webrtc/fonts/Roboto/roboto-light.woff2' => resource_path('js/assets/webrtc/fonts/Roboto/roboto-light.woff2'),
            __DIR__.'/../resources/js/assets/webrtc/images/face-profile.jpg' => resource_path('js/assets/webrtc/images/face-profile.jpg'),
            __DIR__.'/../resources/js/assets/webrtc/images/medal.png' => resource_path('js/assets/webrtc/images/medal.png'),
            __DIR__.'/../resources/js/assets/webrtc/images/pirate-hat.webp' => resource_path('js/assets/webrtc/images/pirate-hat.webp'),
            __DIR__.'/../resources/js/assets/webrtc/scss/DefaultThemeStyle.scss' => resource_path('js/assets/webrtc/scss/DefaultThemeStyle.scss'),
            __DIR__.'/../resources/js/components/webrtc/Rooms.vue' => resource_path('js/components/webrtc/Rooms.vue'),
            __DIR__.'/../resources/js/components/webrtc/RoomCreate.vue' => resource_path('js/components/webrtc/RoomCreate.vue'),
            __DIR__.'/../resources/js/components/webrtc/RoomsList.vue' => resource_path('js/components/webrtc/RoomsList.vue'),
            __DIR__.'/../resources/js/components/webrtc/RoomJoin.vue' => resource_path('js/components/webrtc/RoomJoin.vue'),
            __DIR__.'/../resources/js/components/webrtc/VideoConference.vue' => resource_path('js/components/webrtc/VideoConference.vue'),
            __DIR__.'/../resources/js/components/webrtc/actions/AdmitAction.vue' => resource_path('js/components/webrtc/actions/AdmitAction.vue'),
            __DIR__.'/../resources/js/components/webrtc/actions/BanAction.vue' => resource_path('js/components/webrtc/actions/BanAction.vue'),
            __DIR__.'/../resources/js/components/webrtc/actions/CanvasTextAction.vue' => resource_path('js/components/webrtc/actions/CanvasTextAction.vue'),
            __DIR__.'/../resources/js/components/webrtc/actions/FaceApiAction.vue' => resource_path('js/components/webrtc/actions/FaceApiAction.vue'),
            __DIR__.'/../resources/js/components/webrtc/actions/MultiAction.vue' => resource_path('js/components/webrtc/actions/MultiAction.vue'),
            __DIR__.'/../resources/js/components/webrtc/actions/MuteUserMicAction.vue' => resource_path('js/components/webrtc/actions/MuteUserMicAction.vue'),
            __DIR__.'/../resources/js/components/webrtc/actions/TerminateAction.vue' => resource_path('js/components/webrtc/actions/TerminateAction.vue'),
            __DIR__.'/../resources/js/components/webrtc/themes/CanvasfaceVideoConference.vue' => resource_path('js/components/webrtc/themes/CanvasfaceVideoConference.vue'),
            __DIR__.'/../resources/js/components/webrtc/themes/DefaultVideoConference.vue' => resource_path('js/components/webrtc/themes/DefaultVideoConference.vue'),
            __DIR__.'/../resources/js/components/webrtc/modules/ChatModule.vue' => resource_path('js/components/webrtc/modules/ChatModule.vue'),
            __DIR__.'/../resources/js/components/webrtc/modules/PeopleModule.vue' => resource_path('js/components/webrtc/modules/PeopleModule.vue'),
            __DIR__.'/../resources/js/components/webrtc/modules/CommandsDeckModule.vue' => resource_path('js/components/webrtc/modules/CommandsDeckModule.vue'),
            __DIR__.'/../resources/js/router/router-video-conference.js' => resource_path('js/router/router-video-conference.js'),
            __DIR__.'/../resources/js/router/webrtc.js' => resource_path('js/router/webrtc.js'),
            __DIR__.'/../resources/js/utils/Webrtc/actions/admitAction.js' => resource_path('js/utils/Webrtc/actions/admitAction.js'),
            __DIR__.'/../resources/js/utils/Webrtc/actions/banAction.js' => resource_path('js/utils/Webrtc/actions/banAction.js'),
            __DIR__.'/../resources/js/utils/Webrtc/actions/chatAction.js' => resource_path('js/utils/Webrtc/actions/chatAction.js'),
            __DIR__.'/../resources/js/utils/Webrtc/actions/canvasTextAction.js' => resource_path('js/utils/Webrtc/actions/canvasTextAction.js'),
            __DIR__.'/../resources/js/utils/Webrtc/actions/faceApiAction.js' => resource_path('js/utils/Webrtc/actions/faceApiAction.js'),
            __DIR__.'/../resources/js/utils/Webrtc/actions/muteUserMicAction.js' => resource_path('js/utils/Webrtc/actions/muteUserMicAction.js'),
            __DIR__.'/../resources/js/utils/Webrtc/actions/terminateAction.js' => resource_path('js/utils/Webrtc/actions/terminateAction.js'),
            __DIR__.'/../resources/js/utils/Webrtc/modules/Events.js' => resource_path('js/utils/Webrtc/modules/Events.js'),
            __DIR__.'/../resources/js/utils/Webrtc/modules/Media.js' => resource_path('js/utils/Webrtc/modules/Media.js'),
            __DIR__.'/../resources/js/utils/Webrtc/modules/People.js' => resource_path('js/utils/Webrtc/modules/People.js'),
            __DIR__.'/../resources/js/utils/Webrtc/modules/Room.js' => resource_path('js/utils/Webrtc/modules/Room.js'),
            __DIR__.'/../resources/js/utils/Webrtc/modules/MediapipeBodySegment.js' => resource_path('js/utils/Webrtc/modules/MediapipeBodySegment.js'),
            __DIR__.'/../resources/js/utils/Webrtc/modules/MediapipeFaceDetect.js' => resource_path('js/utils/Webrtc/modules/MediapipeFaceDetect.js'),
            __DIR__.'/../resources/js/utils/Webrtc/Axios.js' => resource_path('js/utils/Webrtc/Axios.js'),
            __DIR__.'/../resources/js/utils/Webrtc/PeerJs.js' => resource_path('js/utils/Webrtc/PeerJs.js'),
            __DIR__.'/../resources/js/utils/Webrtc/Socket.js' => resource_path('js/utils/Webrtc/Socket.js'),
            __DIR__.'/../resources/js/utils/Webrtc/WebRTC.js' => resource_path('js/utils/Webrtc/WebRTC.js'),
            __DIR__.'/../resources/js/utils/Webrtc/helper.vue' => resource_path('js/utils/Webrtc/helper.vue'),
            __DIR__.'/../resources/js/configs/webrtc.js' => resource_path('js/configs/webrtc.js'),
        ], 'videoconference-vue-force');

        $this->publishes([
            __DIR__.'/../resources/js/components/webrtc/VideoConferenceActions.vue' => resource_path('js/components/webrtc/VideoConferenceActions.vue'),
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
