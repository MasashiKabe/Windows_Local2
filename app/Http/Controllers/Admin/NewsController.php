<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//以下追記することでNews Modelが扱えるPHP_Laravel15
use App\News;
//PHP_Laravel18で追記
use App\History;
use Carbon\Carbon;
//heroku画像アップロードで追加
use Storage;

class NewsController extends Controller
{
    //以下を追記
    public function add()
    {
        return view('admin.news.create');
    }

    //以下を追記
    public function create(Request $request)
    {

        //PHP_Laravel15で以下を追記
        //Validationを行う
        $this->validate($request, News::$rules);

        $news = new News;
        $form = $request->all();
        //フォームから画像が送信されてきたら保存して、$news->image_pathに画像のパスを保存する
        if ($form['image']) {
            $path = Storage::disk('s3')->putfile('/',$form['image'],'public');
            $news->image_path = Storage::disk('s3')->url($path);
        } else {
            $news->image_path = null;
        }

        //フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        //フォームから送信されてきたimageを削除する
        unset($form['image']);

        //データベースに保存する
        $news->fill($form);
        $news->save();

        //admin/news/createにリダイレクトする
        return redirect('admin/news/create');
    }

    //PHP_Laravel16で以下を追記
    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            //検索されたら検索結果を取得する
            $posts = News::where('title', $cond_title)->get();
        } else {
            //それ以外はすべてのニュースを取得する
            $posts = News::all();
        }
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }

    //PHP_Laravel17で以下追記
    public function edit(Request $request)
    {
        //News Modelからデータを取得する
        $news = News::find($request->id);

        return view('admin.news.edit', ['news_form' => $news]);
    }

    public function update(Request $request)
    {
        //validationをかける
        $this->validate($request, News::$rules);
        //News Modelからデータを取得する
        $news = News::find($request->id);
        //送信されてきたフォームデータを格納する
        $news_form = $request->all();
        if ($request->input('remove')) {
            $news_form['image_path'] = null;
        } elseif ($request->file('image')) {
            $path = Storage::disk('s3')->putfile('/',$form['image'],'public');
            $news_form['image_path'] = Storage::disk('s3')->url($path); 
        } else {
            $news_form['image_path'] = $news->image_path;
        }

        unset($news_form['_token']);
        unset($news_form['image']);
        unset($news_form['remove']);
        //該当するデータを上書きして保存する
        $news->fill($news_form)->save();

        //PHP_Laravel18で追記
        $history = new History();
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();

        return redirect('admin/news/');
    }

    //PHP_Laravel17で以下追記
    public function delete(Request $request)
    {
        //該当するNews Modelを取得
        $news = News::find($request->id);
        //削除する
        $news->delete();
        return redirect('admin/news/');
    }
}