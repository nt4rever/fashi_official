<?php

namespace App\View\Components;

use Illuminate\Support\Facades\URL;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Recusive extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $data;
    private $htmlSlelect = '';
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */

    public function render()
    {
        return view('components.recusive');
    }


    //for search
    public  function categoryRecusive($parentId, $id = 0, $text = '')
    {
        foreach ($this->data as $value) {
            if ($value['category_parentId'] == $id) {
                if (!empty($parentId) && $parentId == $value['category_id']) {
                    $this->htmlSlelect .= "<option selected value='" . $value['category_id'] . "'>" . $text . $value['category_name'] . "</option>";
                } else {
                    $this->htmlSlelect .= "<option value='" . $value['category_id'] . "'>" . $text . $value['category_name'] . "</option>";
                }
                $this->categoryRecusive($parentId, $value['category_id'], $text . '--');
            }
        }
        return $this->htmlSlelect;
    }

    // for homepage user
    public  function categoryRecusiveHome($parentId, $id = 0, $text = '')
    {
        foreach ($this->data as $value) {
            if ($value['category_parentId'] == $id) {
                if (!empty($parentId) && $parentId == $value['category_id']) {
                    $href = $value['category_slug'];
                    $this->htmlSlelect .= "<li><a href='#' data-id='$href'>" . $text . $value['category_name'] . "(" . $value->count_product() . ")</a></li>";
                } else {
                    $href = $value['category_slug'];
                    if ($value['category_parentId'] == 0) {
                        $this->htmlSlelect .= "<li><a href='#' data-id='$href'>" . $text . $value['category_name'] . "</a></li>";
                    } else {
                        $this->htmlSlelect .= "<li><a href='#' data-id='$href'>" . $text . $value['category_name'] . " (" . $value->count_product() . ")</a></li>";
                    }
                }
                $this->categoryRecusiveHome($parentId, $value['category_id'], $text . '--');
            }
        }

        return $this->htmlSlelect;
    }
    //end for home page use
}
