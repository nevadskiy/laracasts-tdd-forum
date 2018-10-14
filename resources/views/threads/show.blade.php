@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#">{{ $thread->creator->name }}</a> posted: {{ $thread->title }}
                    </div>
                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach ($thread->replies as $reply)
                    @include ('threads.reply')
                @endforeach
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                @auth
                    <form method="POST" action="{{ route('replies.store', $thread) }}">
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
        </div>
    </div>
@endsection
