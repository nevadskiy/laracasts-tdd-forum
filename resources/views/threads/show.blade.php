@extends('layouts.app')

@section('content')
    <thread-view :init-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">
                                    <img src="{{ $thread->creator->avatar() }}" width="36" height="36" class="mr-1">
                                    {{ $thread->title }}
                                </h4>

                                @can('delete', $thread)
                                    <form action="{{ $thread->path() }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>

                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a>, and currently has @{{ repliesCount }} {{ str_plural('comment', $thread->replies->count()) }}
                            </div>

                            <subscribe :status="{{ var_export($thread->isSubscribed) }}"></subscribe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
