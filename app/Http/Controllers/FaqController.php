<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class FaqController extends Controller
{
    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/admin')->send();
        }
    }

    public function faq()
    {
        $this->AuthLogin();
        $faq = Faq::paginate(10);
        return view('admin.static_page.faq', compact('faq'));
    }

    public function add_faq()
    {
        $this->AuthLogin();
        return view('admin.static_page.add_faq');
    }

    public function edit_faq($id)
    {
        $this->AuthLogin();
        $faq = Faq::where('faq_id', $id)->first();
        return view('admin.static_page.edit_faq', compact('faq'));
    }

    public function save_faq(Request $request)
    {
        $this->AuthLogin();
        $data['faq_question'] = $request->faq_question;
        $data['faq_answer'] = $request->faq_answer;
        Faq::insert($data);
        return Redirect::to('/ad/faq');
    }

    public function save_edit_faq(Request $request, $id)
    {
        $this->AuthLogin();
        $data['faq_question'] = $request->faq_question;
        $data['faq_answer'] = $request->faq_answer;
        Faq::where('faq_id', $id)->update($data);
        return Redirect::to('/ad/faq');
    }

    public function delete_faq(Request $request)
    {
        $this->AuthLogin();
        Faq::where('faq_id', $request->id)->delete();
        echo "true";
    }

    public function add_question(Request $request)
    {
        $this->validate($request, [
            'name' => "required",
            'email' => "email|required",
            'question' => "required"
        ]);
        $new = new  Question();
        $new->name = $request->name;
        $new->email = $request->email;
        $new->question = $request->question;
        $new->save();
        return redirect()->back()->with('success', 'Send your question success!');
    }

    public function list_question()
    {
        $ques = Question::paginate(15);
        return view('admin.static_page.question_customer', compact('ques'));
    }

    public function delete_question(Request $request)
    {
        $ques = Question::findOrFail($request->id);
        $ques->delete();
        return true;
    }
}
