@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profiles.show', $user) }}" class="mb-1">{{ $user->name }}</a>
        published "<a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>"
        {{ $activity->created_at->diffForHumans() }}
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
