@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h1 class="d-flex align-items-center justify-content-between">
                            <span>{{ $user->name }}</span>
                            <small>Since {{ $user->created_at->diffForHumans() }}</small>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @foreach ($threads as $thread)
                    <article class="mb-4">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <h4 class="d-flex flex-column">
                                <a href="{{ $thread->path() }}" class="mb-1">{{ $thread->title }}</a>
                                <small>{{ $thread->created_at->diffForHumans() }}</small>
                            </h4>
                            <strong><a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}</a></strong>
                        </div>
                        <div class="body">{{ $thread->body }}</div>
                    </article>
                @endforeach

                {{ $threads->links() }}
            </div>
        </div>
    </div>
@endsection