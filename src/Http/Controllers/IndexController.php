<?php

namespace YayDigital\FailedJobsMonitor\Http\Controllers;

use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function show()
    {
        $jobs = DB::table('failed_jobs')
            ->paginate();

        $jobs->getCollection()->transform(function ($job) {
                $payload = json_decode($job->payload);
                $command = unserialize($payload->data->command);
                preg_match('/(.*?) in \//', $job->exception, $exception);

                return [
                    'id' => $job->id,
                    'class' => get_class($command),
                    'exception' => optional($exception)[1],
                    'failed_at' => $job->failed_at
                ];
            });

        return view('failed-jobs-monitor::index', [
            'jobs' => $jobs,
        ]);
    }
}
