@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h1 class="d-flex align-items-center justify-content-between mb-0">
                            <span>{{ $user->name }}</span>
                            <small>Since {{ $user->created_at->diffForHumans() }}</small>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-8">
                @foreach ($threads as $thread)
                    @include('threads._item')
                @endforeach

                {{ $threads->links() }}
            </div>
        </div>
    </div>
@endsection