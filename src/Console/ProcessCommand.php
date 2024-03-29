<?php

namespace Codenidus\VideoConference\Console;

use Illuminate\Console\Command;

class ProcessCommand extends Command
{
    protected $signature = 'videoconference:install';

    protected $description = 'Install Video Conference Package';

    public function handle()
    {
        $this->publishConfigFile();
        $this->publishMiddlewareFile();
        $this->publishVueAssets();
        $this->writeCommentOnScreen();
    }

    protected function publishVueAssets()
    {
        $this->info('Publishing Video Conference Vue Assets...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'videoconference-vue-force',
            '--force' => true,
        ]);
        $this->callSilent('vendor:publish', ['--tag' => 'videoconference-vue']);
        $this->callSilent('vendor:publish', ['--tag' => 'mediapipe-models']);
    }

    protected function publishConfigFile()
    {
        if (!file_exists(config_path('video-conference.php'))) {
            $this->info('Create Video Conference Configs...');
            $this->callSilent('vendor:publish', [
                '--tag' => 'videoconference-config',
                '--force' => true,
            ]);
        }
    }

    protected function publishMiddlewareFile()
    {
        if (!file_exists(app_path('Http/Middleware/VideoConferenceAuthorize.php'))) {
            $this->info('Create Video Conference Middleware...');
            $this->callSilent('vendor:publish', [
                '--tag' => 'videoconference-middleware',
                '--force' => true,
            ]);
        }
    }

    protected function writeCommentOnScreen()
    {
        $this->warn('Please install dependencies packages by running \'npm install vue'
            .' vue-loader vue-router sass sass-loader axios peerjs socket.io-client@^4.1.2\' ');
			
		$this->warn('Please install medipipe packages by running \'npm install'
            .' @mediapipe/face_detection @mediapipe/selfie_segmentation\' ');
			
		$this->warn('Please install tensorflow packages by running \'npm install'
		.' @tensorflow-models/body-segmentation @tensorflow-models/face-detection @tensorflow/tfjs-backend-webgl'
		.' @tensorflow/tfjs-converter @tensorflow/tfjs-core\' ');	
    }
}
