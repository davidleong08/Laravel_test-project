<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soup; // 假設你有一個Soup模型對應你的數據

class HomeController extends Controller
{
    public function index(Request $request)
{
    $season = $request->query('season', $this->getSeasonNameByMonth(date('n')));
    $soups = $season ? Soup::where('season', $season)->get() : $this->getSeasonalSoups();



    // 如果是 AJAX 请求则返回 JSON，否则返回视图
    if ($request->ajax()) {
        return response()->json($soups);
    } else {
        return view('home', ['soups' => $soups]);
    }
}
    protected function getSeasonalSoups()
    {
        $seasonName = $this->getSeasonNameByMonth(date('n')); // 获取当前季节的名称

        // 直接通过季节名称查询soups表
        return Soup::where('season', $seasonName)->get();
    }

    protected function getSeasonNameByMonth($month)
    {
        if (in_array($month, [12, 1, 2])) {
            return 'winter';
        } elseif (in_array($month, [3, 4, 5])) {
            return 'spring';
        } elseif (in_array($month, [6, 7, 8])) {
            return 'summer';
        } elseif (in_array($month, [9, 10, 11])) {
            return 'autumn';
        }
        // 默认返回一个值，以防万一
        return 'winter'; // 或者你可以选择抛出一个异常
    }
    public function show($id)
    {
        $soup = Soup::findOrFail($id);
        return view('soupdetail', compact('soup'));
    }
}
