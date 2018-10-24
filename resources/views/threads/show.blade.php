@extends('layouts.app')

@section('content')
    <thread-view :init-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">{{ $thread->title }}</h4>

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

                    <replies @removed="repliesCount--" :data="{{ $thread->replies }}"></replies>

                    {{--{{ $replies->links() }}--}}

                    @auth
                        <form method="POST" action="{{ $thread->path() . '/replies' }}">
                            @csrf
                            <div class="form-group">
                        <textarea class="form-control" name="body" id="body" cols="30" rows="2"
                                  placeholder="Your reply..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Post</button>
                        </form>
                    @else
                        <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
                    @endauth
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="#">{{ $thread->creator->name }}</a>, and currently has @{{ repliesCount }} {{ str_plural('comment', $thread->replies->count()) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
