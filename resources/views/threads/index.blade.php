@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                @forelse($threads as $thread)
                    @include('threads._item')
                    <hr>
                @empty
                    <div class="text-center">
                        <p>There are no relevant results at this time.</p>
                    </div>
                @endforelse
                {{ $threads->links() }}
            </div>

            <div class="col-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            Search
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="/threads/search" method="GET">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search for something" name="q">
                            </div>

                            <button class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>

                @if (count($trending))
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            Trending treads
                        </h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path) }}">{{ $thread->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
