<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;

use App\Models\ProductFile;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Request;

class ProductFileController extends AdminController
{
    protected function title()
    {
        return trans('admin.files');
    }

    protected function grid()
    {
        return new Grid(new ProductFile(), function (Grid $grid) {
            $grid->paginate(5);
            $grid->model()->whereHas('product', function($q) { $q->whereManagerId(Admin::user()->id);});

            $grid->product()->title('Product')->badge('blue');
            $grid->path('File')->downloadable();
            $grid->type('Type')->sortable();

            $grid->created_at();
            $grid->updated_at();

            $grid->filter(function ($filter) {
                $filter->equal('product_id', 'Product')->select(Product::whereManagerId(Auth('admin')->user()->id)->pluck('title', 'id'));
                $filter->like('name');
                $filter->disableIdFilter();
            });
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
            $grid->rows(function ($row) {
            });
        });
    }

    protected function form()
    {
        return new Form(new ProductFile(), function (Form $form) {
            $form->display('id', 'ID');
            $products = Product::whereManagerId(Admin::user()->id)->pluck('title', 'id');
            $def = Request::get('product_id');
            $form->select('product_id', 'Product')->options($products)->default($def)->required();

            if ($form->isEditing()) {
                $form->display('name', 'Name');
            }

            if($form->isCreating())
                $form->file('path', __('File'))->required();
            else
                $form->file('path', __('File'));


            $form->select('type', 'Type')->options(['Indicator'=>'Indicator','Expert'=>'Expert','File'=>'File'])->required();
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function ($form) {
                if (!is_null($form->path)) {
                    $form->model()->name = $form->path->getClientOriginalName();
                }
            });
        });
    }

}
