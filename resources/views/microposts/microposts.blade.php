@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $micropost)
        
            <li class="media mb-4">
                {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                <img class="mr-2 rounded" src="{{ Gravatar::get($micropost->user->email, ['size' => 50]) }}" alt="">
                <div class="media-body">
                    <div>
                        {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                        {!! link_to_route('users.show', $micropost->user->name, ['user' => $micropost->user->id]) !!}
                        <span class="text-muted">posted at {{ $micropost->created_at }}.</span>
                        <span class="text-muted">posted id {{ $micropost->id }}</span>
                    </div>
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                    </div>
                    <div class="d-flex">
                        @if (Auth::id() == $micropost->user_id)
                            {{-- 投稿削除ボタンのフォーム --}}
                            {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm mr-3']) !!}
                            {!! Form::close() !!}
                        @endif
                        
                        @if(Request::routeIs('microposts.index'))
                        @else

                            @if (Auth::user()->is_doing_favorite($micropost->id))
                                {{-- お気に入り解除ボタンのフォーム --}}
                                {!! Form::open(['route' => ['favorites.unfavorite', $micropost], 'method' => 'delete']) !!}
                                    {!! Form::submit('Unfavorite', ['class' => "btn btn-warning btn-sm"]) !!}
                                {!! Form::close() !!}
                            @else
                                {{-- お気に入りボタンのフォーム --}}
                                {!! Form::open(['route' => ['favorites.favorite', $micropost], 'method' => 'post']) !!}
                                    {!! Form::submit('favorite', ['class' => "btn btn-primary btn-sm"]) !!}
                                {!! Form::close() !!}
                            @endif

                            
                            
                        @endif

                       

                    </div>
                </div>
            </li>
            <hr>
        @endforeach
        
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif