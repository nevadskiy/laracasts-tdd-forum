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

                    @can('update', $user)
                        <form method="POST" action="{{ route('avatar', $user) }}" enctype="multipart/form-data" class="p-3">
                            @csrf
                            <input type="file" name="avatar">
                            <button type="submit" class="btn btn-primary">Add avatar</button>
                        </form>
                    @endcan

                    <img src="{{ $user->avatar() }}" width="200" height="200">
                </div>
            </div>
            <div class="col-8">
                @forelse ($activities as $date => $activity)
                    <h3 class="px-2">{{ $date }}</h3>
                    @foreach ($activity as $record)
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity for this user yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
