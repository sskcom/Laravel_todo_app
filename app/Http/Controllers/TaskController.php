<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; //追加
use Illuminate\Support\Facades\Validator; //追加


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $before_tasks = Task::all();
        
        //完了前のタスクを取得
        $before_tasks = Task::where('user_id', auth()->id())->
                       where('status', false)->
                       get();

        //完了後のタスクを取得
        $after_tasks = Task::where('user_id', auth()->id())->
        where('status', true)->
        get();

        // return view('dboard.dashboard', compact('before_tasks'));

        return view('dboard.dashboard', [
            'before_tasks' => $before_tasks,
            'after_tasks' => $after_tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'task_name' => 'required|max:100',
        ];

        $messages = ['required' => '必須項目です', 'max' => '100文字以下にしてください。'];

        Validator::make($request->all(), $rules, $messages)->validate();



        //モデルをインスタンス化
        $task = new Task;

        //モデル->カラム名 = 値 で、データを割り当てる
        $task->name = $request->input('task_name');
        $task->user_id = auth()->id();





        //データベースに保存
        $task->save();

        //リダイレクト
        // return redirect('/tasks');
        return redirect('/dboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //「編集する」ボタンをおしたとき
        if ($request->status === null) {
            $rules = [
                'task_name' => 'required|max:100',
            ];

            $messages = ['required' => '必須項目です', 'max' => '100文字以下にしてください。'];

            Validator::make($request->all(), $rules, $messages)->validate();


            //該当のタスクを検索
            $task = Task::find($id);

            //モデル->カラム名 = 値 で、データを割り当てる
            $task->name = $request->input('task_name');

            //データベースに保存
            $task->save();
        } else {
            //「完了」ボタンを押したとき
            

            
            //該当のタスクを検索
            $task = Task::find($id);

            // //モデル->カラム名 = 値 で、データを割り当てる
            $task->status = true; //true:完了、false:未完了

            // //データベースに保存
            $task->save();
        }


        //リダイレクト
        // return redirect('/tasks');
        return redirect('/dboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::find($id)->delete();

        //  return redirect('/tasks');
        return redirect('/dboard');
    }
}
