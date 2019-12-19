@extends('layout')

@section('content')
    <h1 class="mt-5">Job Details: <code>{{ $class }}</code> (ID: {{ $id }})</h1>

    <h5>Failed At</h5>
    <p>{{ $failed_at }}</p>

    <h5>Payload</h5>
{{--    <pre><code>{!! json_encode($command, JSON_PRETTY_PRINT) !!}</code></pre>--}}
    <pre>@php(var_dump($command))</pre>

    <h5>Exception</h5>
    <pre>{{ $exception }}</pre>
@endsection
