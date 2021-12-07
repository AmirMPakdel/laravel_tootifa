<?php


namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Category;



class CategoriesController extends BaseController
{
    public function fetchCategories(){
        $categories = Category::all()->map(function ($cat){
            return [
                'id' => $cat->id,
                'title' => $cat->title
            ];
        });

        return $this->sendResponse(Constant::$SUCCESS, $categories);
    }
}
