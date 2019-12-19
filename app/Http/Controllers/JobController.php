<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function show($id)
    {
        $job = $this->getJob($id);

        $payload = json_decode($job->payload);
        $command = unserialize($payload->data->command);

        return view('show', [
            'id' => $job->id,
            'class' => get_class($command),
            'command' => $command,
            'exception' => $job->exception,
            'failed_at' => $job->failed_at
        ]);
    }

    public function retry($id)
    {
        $this->getJob($id);

        Artisan::call('queue:retry '.$id);

        return redirect(route('index'));
    }

    public function confirmDelete($id)
    {
        $job = $this->getJob($id);

        $payload = json_decode($job->payload);
        $command = unserialize($payload->data->command);

        return view('delete', [
            'id' => $job->id,
            'class' => get_class($command),
        ]);
    }

    public function delete($id)
    {
        DB::table('failed_jobs')
            ->where('id', '=', $id)
            ->delete();

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
}
