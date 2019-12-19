@extends('failed-jobs-monitor::layout')

@section('content')
    <h1 class="mt-5">Job Details: <code>{{ $class }}</code> (ID: {{ $id }})</h1>

    <h5>Failed At</h5>
    <p>{{ $failed_at }}</p>

    <h5>Job</h5>
    <pre>@dump($command)</pre>

    <h5>Error</h5>
    <p><code>{{ $exception['error'] }}</code></p>

    <h5>Location</h5>
    <p><code>{{ $exception['location'] }}</code></p>

    <h5>Stack</h5>
    <pre><code>{{ $exception['stack'] }}</code></pre>
@endsection
