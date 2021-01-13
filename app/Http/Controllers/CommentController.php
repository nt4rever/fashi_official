<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentPost;
use App\Models\Customer;
use App\Models\Product;
use App\View\Components\Recusive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function list_product_comment($id)
    {
        $comment = $this->comment->where('product_id', $id)->orderBy('comment_id', 'desc')->paginate(25);
        $product = Product::find($id);
        return view('admin.comment.list_product_comment', compact('comment', 'product'));
    }

    public function add_comment(Request $request)
    {
        try {
            $customer_id = Session::get('customer_id');
            if (!$customer_id) {
                return false;
            }
            DB::beginTransaction();
            $data = array();
            $reply = $request->reply;
            if (isset($reply)) {
                $data['reply_id'] = $reply;
            }
            $data['product_id'] = $request->product_id;
            $data['customer_id'] = $customer_id;
            $data['comment_content'] = $request->comment_content;
            $data['comment_time'] = $request->comment_time;
            $id = $this->comment->insertGetId($data);
            DB::commit();
            $res = Comment::where('comment_id', $id)->first();
            $comment = Comment::where('product_id', $res->product_id)->where('reply_id', 0)
                ->orderBy('comment_id', 'desc')->limit(15)->get();

            $count_comment = 0;
            $comment_str = '';
            foreach ($comment as $item) {
                $comment_str = $comment_str . '
                <div class="co-item">
                    <div class="avatar-pic">';
                if ($item->customer->customer_image == null) {
                    $comment_str = $comment_str . ' <div class="logo-customer">
                        <span>' . $item->customer->customer_name[0] . '</span>
                    </div>';
                } else {
                    $comment_str = $comment_str . '<img src="' . $item->customer->customer_image . '" style="max-width: 50px;max-height: 50px"
                    alt="">';
                }

                $comment_str = $comment_str . '</div>
                    <div class="avatar-text">
                        <div class="at-rating">';
                if ($item->rating) {
                    for ($i = 1; $i <= $item->rating->rating; $i++) {
                        $comment_str = $comment_str . '<i class="fa fa-star"></i> ';
                    }
                    for ($i = 1; $i <= 5 - $item->rating->rating; $i++) {
                        $comment_str = $comment_str . ' <i class="fa fa-star-o"></i> ';
                    }
                }
                $comment_str = $comment_str . '</div>
                        <h5>' . $item->customer->customer_name . '<span>' . $item->comment_time . '</span>
                        </h5>
                        <div class="at-reply">' . $item->comment_content . '</div>
                        <div class="at-reply"><a href="" class="comment-reply"
                                data-id="' . $item->comment_id . '"
                                data-name="' . $item->customer->customer_name . '">Reply</a>';
                if (Session::get('customer_id') == $item->customer->customer_id) {
                    $comment_str = $comment_str . '<a href="#" class="comment-remove"
                    data-id="' . $item->comment_id . '">- Remove</a>';
                }
                $comment_str = $comment_str . '</div></div></div>';
                $count_comment++;
                if (isset($item->childComment)) {
                    foreach ($item->childComment as $child) {
                        $comment_str = $comment_str . '
                        <div class="co-item ml-5 pl-2">
                        <div class="avatar-pic">
                            <span><i class="fa fa-reply" style="color: #ff98008a;
                                transform: rotate(180deg);"></i></span>
                        </div>
                        <div class="avatar-pic">';
                        if ($child->customer->customer_image == null) {
                            $comment_str = $comment_str . ' <div class="logo-customer">
                                <span>' . $child->customer->customer_name[0] . '</span>
                            </div>';
                        } else {
                            $comment_str = $comment_str . '<img src="' . $child->customer->customer_image . '" style="max-width: 50px;max-height: 50px"
                            alt="">';
                        }

                        $comment_str = $comment_str . '
                        </div>
                        <div class="avatar-text">
                            <div class="at-rating">';
                        if ($child->rating) {
                            for ($i = 1; $i <= $child->rating->rating; $i++) {
                                $comment_str = $comment_str . '<i class="fa fa-star"></i> ';
                            }
                            for ($i = 1; $i <= 5 - $child->rating->rating; $i++) {
                                $comment_str = $comment_str . '<i class="fa fa-star-o"></i> ';
                            }
                        }
                        $comment_str = $comment_str . '
                            </div>
                            <h5>' . $child->customer->customer_name . '<span>' . $child->comment_time . '</span>
                            </h5>
                            <div class="at-reply">' . $child->comment_content . '</div>';
                        if (Session::get('customer_id') == $child->customer->customer_id) {
                            $comment_str = $comment_str . '<div class="at-reply"><a href="#" class="comment-remove"
                                    data-id="' . $child->comment_id . '">Remove</a></div>';
                        }

                        $comment_str = $comment_str . '</div>
                    </div>
                        ';
                        $count_comment++;
                    }
                }
            }
            $data['count'] = $count_comment;
            $data['comment'] = $comment_str;
            return json_encode($data);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function delete_comment_customer(Request $request)
    {
        $comment_id = $request->comment_id;
        $cmt = Comment::findOrFail($comment_id);
        $product_id = $cmt->product_id;
        if ($cmt) {
            if (isset($cmt->childComment)) {
                foreach ($cmt->childComment as $item) {
                    Comment::findOrFail($item->comment_id)->delete();
                }
            }
            $cmt->delete();
        }
        $comment = Comment::where('product_id', $product_id)->where('reply_id', 0)
            ->orderBy('comment_id', 'desc')->limit(15)->get();
        $count_comment = 0;
        $comment_str = '';
        foreach ($comment as $item) {
            $comment_str = $comment_str . '
                <div class="co-item">
                    <div class="avatar-pic">';
            if ($item->customer->customer_image == null) {
                $comment_str = $comment_str . ' <div class="logo-customer">
                            <span>' . $item->customer->customer_name[0] . '</span>
                        </div>';
            } else {
                $comment_str = $comment_str . '<img src="' . $item->customer->customer_image . '" style="max-width: 50px;max-height: 50px"
                        alt="">';
            };

            $comment_str = $comment_str . '
                    </div>
                    <div class="avatar-text">
                        <div class="at-rating">';
            if ($item->rating) {
                for ($i = 1; $i <= $item->rating->rating; $i++) {
                    $comment_str = $comment_str . '<i class="fa fa-star"></i> ';
                }
                for ($i = 1; $i <= 5 - $item->rating->rating; $i++) {
                    $comment_str = $comment_str . ' <i class="fa fa-star-o"></i> ';
                }
            }
            $comment_str = $comment_str . '</div>
                        <h5>' . $item->customer->customer_name . '<span>' . $item->comment_time . '</span>
                        </h5>
                        <div class="at-reply">' . $item->comment_content . '</div>
                        <div class="at-reply"><a href="" class="comment-reply"
                                data-id="' . $item->comment_id . '"
                                data-name="' . $item->customer->customer_name . '">Reply</a>';
            if (Session::get('customer_id') == $item->customer->customer_id) {
                $comment_str = $comment_str . '<a href="#" class="comment-remove"
                    data-id="' . $item->comment_id . '">- Remove</a>';
            }
            $comment_str = $comment_str . '</div></div></div>';
            $count_comment++;
            if (isset($item->childComment)) {
                foreach ($item->childComment as $child) {
                    $comment_str = $comment_str . '
                        <div class="co-item ml-5 pl-2">
                        <div class="avatar-pic">
                            <span><i class="fa fa-reply" style="color: #ff98008a;
                                transform: rotate(180deg);"></i></span>
                        </div>
                        <div class="avatar-pic">
                        ';
                    if ($child->customer->customer_image == null) {
                        $comment_str = $comment_str . ' <div class="logo-customer">
                                <span>' . $child->customer->customer_name[0] . '</span>
                            </div>';
                    } else {
                        $comment_str = $comment_str . '<img src="' . $child->customer->customer_image . '" style="max-width: 50px;max-height: 50px"
                            alt="">';
                    }

                    $comment_str = $comment_str . '
                        </div>
                        <div class="avatar-text">
                            <div class="at-rating">';
                    if ($child->rating) {
                        for ($i = 1; $i <= $child->rating->rating; $i++) {
                            $comment_str = $comment_str . '<i class="fa fa-star"></i> ';
                        }
                        for ($i = 1; $i <= 5 - $child->rating->rating; $i++) {
                            $comment_str = $comment_str . '<i class="fa fa-star-o"></i> ';
                        }
                    }
                    $comment_str = $comment_str . '
                            </div>
                            <h5>' . $child->customer->customer_name . '<span>' . $child->comment_time . '</span>
                            </h5>
                            <div class="at-reply">' . $child->comment_content . '</div>';
                    if (Session::get('customer_id') == $child->customer->customer_id) {
                        $comment_str = $comment_str . '<div class="at-reply"><a href="#" class="comment-remove"
                                    data-id="' . $child->comment_id . '">Remove</a></div>';
                    }

                    $comment_str = $comment_str . '</div>
                    </div>
                        ';
                    $count_comment++;
                }
            }
        }
        $data['count'] = $count_comment;
        $data['comment'] = $comment_str;
        return json_encode($data);
    }

    public function delete_comment(Request $request)
    {
        try {
            $id = $request->comment_id;
            $this->comment->find($id)->delete();
            echo "true";
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
    public function all_product_comment()
    {
        try {
            $all_comment = Comment::orderBy('comment_id', 'desc')->paginate(25);
            return view('admin.comment.list_all_product_comment', compact('all_comment'));
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function admin_reply(Request $request)
    {
        $adm = Customer::where('customer_email', 'admin@fashi')->first();
        if ($adm) {
            $comment = new Comment();
            $comment->customer_id = $adm->customer_id;
            $comment->comment_content = $request->content;
            $comment->comment_time = Carbon::now();
            $comment->product_id = $request->product_id;
            $comment->reply_id = $request->reply_id;
            $comment->save();
            return redirect()->back()->with('message', 'Reply Thanh cong');
        } else {
            $admin = new Customer();
            $admin->customer_email = 'admin@fashi';
            $admin->customer_name = 'Fashi Shop';
            $admin->customer_password = md5(Str::random(9));
            $admin->save();

            $admin->customer_id;
            $comment = new Comment();
            $comment->customer_id = $admin->customer_id;
            $comment->comment_content = $request->content;
            $comment->comment_time = Carbon::now();
            $comment->product_id = $request->product_id;
            $comment->reply_id = $request->reply_id;
            $comment->save();
            return redirect()->back()->with('message', 'Reply Thanh cong');
        }
    }
}
