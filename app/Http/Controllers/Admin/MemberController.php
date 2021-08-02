<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Member;
use App\Latte;

class MemberController extends Controller
{
    // 配列で変換
    public $gender = array('0'=>'男性(male)', '1'=>'女性(female)');
    
    
    //一覧 DBのEloquent
    public function select(){
    $members = \App\Member::all();
    return view('admin.mypage')->with('members',$members);
    }
    
    
    public function mypage(Request $request) 
    {
        $members = Member::all();
        $lattes = Latte::all();
        
        return view('admin.mypage', ['members' => $members, 'lattes' => $lattes, 'gender' => $this->gender]);
    }
    
    
    public function add(){
        return view('admin.member.create');
    }
    
    
    public function create(Request $request) 
    {
        $this->validate($request, Member::$rules);
        
        $member = new Member;
        $form = $request->all();
        
        unset($form['_token']);
        
        $member->fill($form);
        $member->save();
       
        return redirect('admin/member/create');
    }
    
    
    public function index(Request $request) 
    {
        // dump($this->gender);
        $cond_title = $request -> cond_title;
        if ($cond_title != '') {
            $posts = Member::where('title', $cond_title) -> get();
        } else {
            $posts = Member::all();
        }
        return view('admin.member.index', ['posts' => $posts, 'cond_title' => $cond_title, 'gender' => $this->gender]);
    }
    
    
    public function edit(Request $request)
    {
      $member = Member::find($request->id);
      if (empty($member)) {
        abort(404);    
      }
      return view('admin.member.edit', ['member_form' => $member]);
    }
    
    
    public function update(Request $request)
    {
      $this->validate($request, Member::$rules);
      $member = Member::find($request->id);
      $member_form = $request->all();
      unset($member_form['_token']);

      $member->fill($member_form)->save();

      return redirect('admin/member/');
     }
    
    
    public function delete(Request $request)
    {
      $member = Member::find($request->id);
      $member->delete();
      return redirect('admin/member/');
    }
    
}
