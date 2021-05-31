<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class FavoritesController extends Controller
{
    
    
    
     /**
     * postをお気に入りするアクション。
     *
     * @param  $id  対象のpostid
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $login_user = \Auth::user();
        //dd($id, $login_user);
        // ログインしているユーザが、 micropostのIDをお気に入りする
        $login_user->favorite($id);
        
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * postをお気に入り解除するアクション。
     *
     * @param  $id  対象のpostid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // ログインしているユーザ（閲覧者）が、 micropostのIDをお気に入り解除する
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
