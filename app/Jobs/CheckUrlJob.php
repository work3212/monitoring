<?php

namespace App\Jobs;

use App\Services\Check\CheckService;
use App\Services\Viber\ViberRestApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class CheckUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $text;
    private $url;

    public function __construct($url, $text)
    {
        $this->url = $url;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CheckService $checkService, ViberRestApiService $viberRestApiService)
    {

        $status = $checkService->checkProjects($this->url, $this->text);
        if (!$status) {
            $viberRestApiService->sendMessageViber('НЕ ПРОШЕЛ ПРОВЕРКУ! '. $this->url);
        }else{
            $viberRestApiService->sendMessageViber($this->url . ' Сайт доступен');
        }
    }
}
