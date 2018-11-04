{{-- Editing --}}
<div class="card mb-3" v-if="editing">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="card-body">
        <textarea class="form-control" rows="5" v-model="form.body"></textarea>
    </div>

    <div class="card-footer d-flex">
        <button v-show="!editing" class="btn btn-primary btn-sm" @click="editing = true">Edit</button>
        <button v-show="editing" class="btn btn-info btn-sm" @click="update">Update</button>
        <button class="btn btn-primary btn-sm ml-2" @click="resetForm">Cancel</button>

        @can('delete', $thread)
            <form action="{{ $thread->path() }}" method="POST" class="ml-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        @endcan
    </div>
</div>

{{-- Viewing --}}
<div class="card mb-3" v-else>
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="mb-0">
                <img src="{{ $thread->creator->avatar_path }}" width="36" height="36" class="mr-1">
                @{{ title }}
            </h4>
        </div>
    </div>

    <div class="card-body" v-text="body"></div>

    <div class="card-footer" v-if="authorize('owns', thread) || authorize('admin')">
        <button class="btn btn-primary btn-sm" @click="editing = true">Edit</button>
    </div>
</div>
