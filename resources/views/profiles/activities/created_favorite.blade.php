@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profiles.show', $user) }}" class="mb-1">{{ $user->name }}</a>
        favorited a <a href="{{ $activity->subject->favorited->path() }}">reply</a> {{ $activity->created_at->diffForHumans() }}
    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent
