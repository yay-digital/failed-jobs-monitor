@extends('layout')

@section('content')
    <table class="table mt-5">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Job</th>
            <th scope="col">Exception</th>
            <th scope="col">Failed At</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($jobs as $job)
            <tr>
                <td>{{ $job['id'] }}</td>
                <td>{{ $job['class'] }}</td>
                <td title="{{ $job['exception'] }}">{{ \Illuminate\Support\Str::limit($job['exception'], 70) }}</td>
                <td>{{ $job['failed_at'] }}</td>
                <td>
                    <a class="action" href="{{ route('show', $job['id']) }}" title="Show Details">
                        <i data-feather="info"></i>
                    </a>
                    <a class="action" href="{{ route('retry', $job['id']) }}" title="Retry Job">
                        <i data-feather="repeat"></i>
                    </a>
                    <a class="action" href="{{ route('confirmDelete', $job['id']) }}" title="Forget Job">
                        <i data-feather="trash-2"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center alert alert-success">
                    No failed jobs.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
