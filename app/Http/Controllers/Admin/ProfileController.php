<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    //PHP/Laravel9応用
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        //validationを行う
        $this->validate($request, Profile::$rules);
        $profile = new Profile;
        $form = $request->all();

        //formに画像があれば保存する
        if ($form['image']) {
            $path = $request->file('image')->store('public/image');
            $profile->image_path = basename($path);
        } else {
            $profile->image_path = null;
        }
        
        unset($form['_token']);
        unset($form['image']);
        //データベースに保存する
        $profile->fill($form);
        $profile->save();

        return redirect('admin/profile/create');
    }

    public function index(Request $request)
    {
        $cond_title = $request->cond_name;
        if ($cond_title != '') {
            //検索されたら検索結果を取得する
            $posts = Profile::where('name', $cond_name)->get();
        } else {
            //それ以外はすべてのプロフィールを取得する
            $posts = Profile::all();
        }
        return view('admin.profile.index', ['posts' => $posts, 'cond_name' => $cond_name]);
    }

    public function edit(Request $request)
    {
        //Profile Modelからデータを取得する
        $profile = Profile::find($request->id);
        if (empty($profile)) {
            abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
        //Validationをかける
        $this->validate($request, Profile::$rules);
        //Profile Modelからデータを取得する
        $profile = Profile::find($request->id);
        //送信されてきたフォームデータを格納する
        $profile_form = $request->all();
        if ($request->remove == 'true') {
            $profile_form['image_path'] = null;
        } elseif ($request->file('image')) {
            $path = $request->file('image')->store('public/image');
            $profile_form['image_path'] = basename($path);
        } else {
            $profile_form['image_path'] = $profile->image_path;
        }

        unset($profile_form['image']);
        unset($profile_form['remove']);
        unset($profile_form['_token']);

        //該当するデータを上書きして保存する
        $profile->fill($profile_form)->save();

        return redirect('admin/profile');
    }
}
