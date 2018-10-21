<article class="mb-4">
    <div class="d-flex flex-row justify-content-between">
        <h4>
            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
        </h4>
        <strong><a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}</a></strong>
    </div>
    <div class="body">{{ $thread->body }}</div>
</article>
