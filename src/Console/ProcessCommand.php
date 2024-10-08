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
        $this->setEnvVariables();
        $this->writeCommentOnScreen();
    }

    protected function publishVueAssets()
    {
        $this->info('Publishing Video Conference Vue Assets...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'videoconference-vue',
            '--force' => true,
        ]);
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
        $this->warn('Please install dependencies packages by running \'npm install cnidus-videoconference-vue\' ');
    }

    protected function setEnvVariables()
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $contents = file_get_contents($path);

            if(strpos($contents, "MIX_WEBRTC_TOKEN_URL") === false) {
                $exampleVariable = file_get_contents(__DIR__.'/.env.example');
                file_put_contents($path, $exampleVariable, FILE_APPEND);

                $this->info('Environment variables in .env was set...');
            }
        }
    }
}
