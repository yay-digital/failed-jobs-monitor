@extends('failed-jobs-monitor::layout')

@section('content')
    <h1 class="mt-5">You really want to delete job <code>{{ $class }}</code> (ID: {{ $id }})?</h1>

    <form action="{{ route('failed-jobs-monitor.delete', $id) }}" method="POST">
        {{ csrf_field() }}
        <div class="text-center">
            <a href="{{ route('failed-jobs-monitor.index') }}" class="btn btn-secondary">No</a>
            <button class="btn btn-danger" type="submit">Yes</button>
        </div>
    </form>
@endsection
