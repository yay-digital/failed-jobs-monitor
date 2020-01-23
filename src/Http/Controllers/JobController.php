<?php

namespace YayDigital\FailedJobsMonitor\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Caster\ReflectionCaster;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;
use YayDigital\FailedJobsMonitor\ExceptionParser;

class JobController extends Controller
{
    public function show($id)
    {
        $this->configureDumper();

        $job = $this->getJob($id);

        $payload = json_decode($job->payload);
        $command = unserialize($payload->data->command);

        $exception = new ExceptionParser($job->exception);

        return view('failed-jobs-monitor::show', [
            'id' => $job->id,
            'class' => get_class($command),
            'command' => $command,
            'exception' => [
                'error' => $exception->getError(),
                'location' => $exception->getLocation(),
                'stack' => $exception->getStack(),
            ],
            'failed_at' => $job->failed_at
        ]);
    }

    public function retry($id)
    {
        $this->getJob($id);

        Artisan::call('queue:retry '.$id);

        session()->flash('info', 'Job '.$id. ' has been queued for retry');

        return redirect(route('index'));
    }

    public function confirmDelete($id)
    {
        $job = $this->getJob($id);

        $payload = json_decode($job->payload);
        $command = unserialize($payload->data->command);

        return view('failed-jobs-monitor::delete', [
            'id' => $job->id,
            'class' => get_class($command),
        ]);
    }

    public function delete($id)
    {
        DB::table('failed_jobs')
            ->where('id', '=', $id)
            ->delete();

        session()->flash('info', 'Job '.$id. ' has been deleted');

        return redirect(route('index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    private function getJob($id)
    {
        $job = DB::table('failed_jobs')
            ->where('id', '=', $id)
            ->first();

        if ($job === null) {
            abort(404);
        }

        return $job;
    }

    protected function configureDumper(): void
    {
        $cloner = new VarCloner();
        $cloner->addCasters(ReflectionCaster::UNSET_CLOSURE_FILE_INFO);

        $dumper = new HtmlDumper();
        $dumper->setTheme('light');
        $dumper->setDisplayOptions([
            'maxDepth' => 2,
        ]);

        VarDumper::setHandler(function ($var) use ($cloner, $dumper) {
            $dumper->dump($cloner->cloneVar($var));
        });
    }
}
