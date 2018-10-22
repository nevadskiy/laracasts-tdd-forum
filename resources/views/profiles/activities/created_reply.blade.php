@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profiles.show', $user) }}" class="mb-1">{{ $user->name }}</a>
        replied to <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
        {{ $activity->created_at->diffForHumans() }}
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
