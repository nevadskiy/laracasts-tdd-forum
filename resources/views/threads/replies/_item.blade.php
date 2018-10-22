<div id="reply-{{ $reply->id }}" class="card mb-3">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <span>
                <a href="{{ route('profiles.show', $reply->owner) }}">{{ $reply->owner->name }}</a>
                <span class="">said {{ $reply->created_at->diffForHumans() }}</span>
            </span>

            <form action="{{ route('replies.favorite', $reply) }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="btn btn-default"{{ $reply->isFavorited() ? ' disabled' : '' }}>
                    {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        {{ $reply->body }}
    </div>
    @can('delete', $reply)
        <div class="card-footer">
            <form action="{{ route('replies.destroy', $reply) }}" method="POST" class="d-flex">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm ml-auto">Delete</button>
            </form>
        </div>
    @endcan
</div>