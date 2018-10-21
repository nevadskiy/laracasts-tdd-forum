@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="px-4 mb-3">Forum threads</h2>

                @forelse($threads as $thread)
                    @include('threads._item')
                    <hr>
                @empty
                   <div class="text-center">
                       <p>There are no relevant results at this time.</p>
                   </div>
                @endforelse
                {{ $threads->links() }}
            </div>
        </div>
    </div>
@endsection
