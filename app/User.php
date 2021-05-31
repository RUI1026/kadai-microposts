<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    
    /**
     * このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    
    /**
     * このユーザに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers','favorites']);
    }
    

    /**
     * このユーザ 　が  フォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザ  を  フォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
    
    
     /***************************************************************************
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    /**
     * 指定された $userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
    public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    
     /**
     * このユーザとフォロー中ユーザの投稿に絞り込む。
     */
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    
    
    /*---------------------favorite--------------------------------*/
    /**
     * このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
     */
    /**
     * このユーザ 　が  お気に入り中の投稿。（ Micropostモデルとの関係を定義）
     * favorites_postsテーブルを参照
     */
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites_posts', 'user_id', 'micropost_id')->withTimestamps()->withPivot('micropost_id');
    }

    
    
    /***************************************************************************
     * $postIdで指定されたmicropostをフォローする。
     *
     * @param  int  $postId
     * @return bool
     */
    public function favorite($postId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_doing_favorite($postId);

        if ($exist) {
            // すでにフォローしていれば何もしない
            return true;
        } else {
            // 未フォローであればフォローする
            $this->favorites()->attach($postId);
            return true;
        }
   
    }

    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $postId
     * @return bool
     */
    public function unfavorite($postId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_doing_favorite($postId);

        
        if ($exist) {
            // すでにフォローしていればフォローを外す
            $this->favorites()->detach($postId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    /**
     * 指定された $postIdのユーザがこのmicropostをフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
    public function is_doing_favorite($postId)
    {
        // お気に入り登録しているかtrue false を返答
        return $this->favorites()->where('micropost_id', $postId)->exists();
    }
    
    
     /**
     * このユーザとフォロー中ユーザの投稿に絞り込む。
     */
    public function feed_microposts_as_favorite()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }

    
    
    
}
