<?php

namespace YayDigital\FailedJobsMonitor\Http\Controllers;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Queue\Failed\FailedJobProviderInterface;
use Illuminate\Support\Collection;
use YayDigital\FailedJobsMonitor\ExceptionParser;

class IndexController extends Controller
{
    /** @var FailedJobProviderInterface */
    private $failedJobProvider;

    public function __construct(FailedJobProviderInterface $failedJobProvider)
    {
        $this->failedJobProvider = $failedJobProvider;
    }

    public function show()
    {
        $jobs = $this->paginate($this->failedJobProvider->all(), 10);

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

    public function paginate($items, $perPage = 5, $page = null, $options = []): AbstractPaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        $options = array_merge(['path' => Paginator::resolveCurrentPath()], $options);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }
}
