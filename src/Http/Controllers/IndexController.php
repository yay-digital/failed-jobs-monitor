<?php

namespace YayDigital\FailedJobsMonitor\Http\Controllers;

use Illuminate\Support\Facades\DB;
use YayDigital\FailedJobsMonitor\ExceptionParser;

class IndexController extends Controller
{
    public function show()
    {
        $jobs = DB::table('failed_jobs')
            ->paginate();

        $jobs->getCollection()->transform(function ($job) {
            $payload = json_decode($job->payload);
            $command = unserialize($payload->data->command);
            $exception = new ExceptionParser($job->exception);

            return [
                'id' => $job->id,
                'class' => get_class($command),
                'error' => $exception->getError(),
                'failed_at' => $job->failed_at,
            ];
        });

        return view('failed-jobs-monitor::index', [
            'jobs' => $jobs,
        ]);
    }
}
