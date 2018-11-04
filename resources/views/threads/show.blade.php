@extends('layouts.app')

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8">
                    @include('threads._thread')

                    <replies
                            @added="repliesCount++"
                            @removed="repliesCount--"
                    ></replies>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a>, and
                                currently has @{{ repliesCount }} {{ str_plural('comment', $thread->replies->count()) }}
                            </div>

                            <subscribe :status="{{ var_export($thread->isSubscribed) }}"></subscribe>

                            <button
                                    v-if="authorize('admin')"
                                    class="btn btn-default"
                                    @click="toggleLock"
                                    v-text="locked ? 'Unlock' : 'Lock'"
                            ></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
