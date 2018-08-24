<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;
class StatusesController extends Controller
{
    public function __construct()
    {
        // 中间件,需要用户登录
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content'   => 'required|max:140'
        ]);

        // 借助 Laravel 提供的 Auth::user() 方法我们可以获取到当前用户实例
        Auth::user()->statuses()->create([
            'content'   => $request['content']
        ]);

        return redirect()->back();
    }

    public function destroy(Status $status)
    {
        // 使用授权策略.
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博删除成功!');
        return redirect()->back();
    }
}
