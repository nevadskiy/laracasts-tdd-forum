<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="card mb-3">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <span>
                    <a href="{{ route('profiles.show', $reply->owner) }}">{{ $reply->owner->name }}</a>
                    <span class="">said {{ $reply->created_at->diffForHumans() }}</span>
                </span>

                @auth
                    <favorite :reply="{{ $reply }}"></favorite>
                @endauth
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button @click="update" class="btn btn-sm btn-primary">Update</button>
                <button @click="editing = false" class="btn btn-sm btn-link">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        @can('delete', $reply)
            <div class="card-footer d-flex">
                <button class="btn btn-sm btn-primary mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger mr-2" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply>