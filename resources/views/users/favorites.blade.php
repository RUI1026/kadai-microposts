@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            {{-- ユーザ情報 --}}
            @include('users.card')
        </aside>
        <div class="col-sm-8">
            {{-- タブ --}}
            @include('users.navtabs')
            {{-- お気に入り一覧 --}}
            <ul class="list-unstyled">
                @foreach ($favorite_microposts as $favorite)
                    <li class="media mb-4">
                        
                        {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
                        <img class="mr-2 rounded" src="{{ Gravatar::get($user_name = App\User::find($favorite->user_id)->email, ['size' => 50]) }}" alt="">
                        <div class="media-body">
                            <div>
                                {{-- お気に入り投稿の所有者のユーザname --}}
                                <span class="text-muted">posted by <?php $user_name = App\User::find($favorite->user_id)->name; echo $user_name;?></span>
                                <span class="text-muted">at {!! $favorite->created_at !!}</span>
                                <span class="text-muted">（post ID {!! $favorite->pivot->micropost_id !!}）</span>
                            </div>
                           
                            <div>
                                {{-- ユーザ詳細ページへのリンク --}}
                                <p>{!! link_to_route('users.show', 'View profile', ['user' => $favorite->user_id]) !!}</p>
                            </div>
                            <div>
                                {{-- お気に入り投稿内容 --}}
                                <p class="mb-0">{!! nl2br(e($favorite->content)) !!}</p>
                            </div>
                        </div>
                        @if (Auth::id() == $user->id)
                            @if ($user->id != $favorite->user_id)
                                {{-- お気に入り解除ボタンのフォーム --}}
                                {!! Form::open(['route' => ['favorites.unfavorite', $favorite->pivot->micropost_id], 'method' => 'delete']) !!}
                                    {!! Form::submit('Unfavorite', ['class' => "btn btn-warning btn-block"]) !!}
                                {!! Form::close() !!}
                            @else
                                {{-- 削除ボタンのフォーム --}}
                                {!! Form::open(['route' => ['microposts.destroy', $favorite->pivot->micropost_id], 'method' => 'delete']) !!}
                                    {!! Form::submit('delete', ['class' => "btn btn-danger btn-block"]) !!}
                                {!! Form::close() !!}
    
                            @endif
                        @endif
                    </li>
                    
                @endforeach
                {{-- ページネーションのリンク --}}
                {{ $favorite_microposts->links() }}
           </ul>
        </div>
        

            

        
    
    </div>
@endsection