<div class="card mb-4">
    <div class="card-header d-flex flex-row justify-content-between">
        <h4 class="d-flex flex-column mb-0">
            <a href="{{ $thread->path() }}" class="mb-1">{{ $thread->title }}</a>
            <small style="font-size: 13px">{{ $thread->created_at->diffForHumans() }} by <a href="{{ route('profiles.show', $thread->creator) }}">{{ $thread->creator->name }}</a></small>
        </h4>
        <strong><a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}</a></strong>
    </div>
    <div class="card-body">
        {{ $thread->body }}
    </div>
</div>
