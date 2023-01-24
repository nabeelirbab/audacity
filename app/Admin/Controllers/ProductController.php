<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Row;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class ProductController extends AdminController
{
    protected function title() {
        return trans('defender.products');
    }

    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));

        $show->key('Key');
        $show->field('title','Title');
        $show->textarea('description','Description');

        $show->disableEditButton();
        $show->disableDeleteButton();

        $show->relation('files', function(Product $product){

            return new Grid($product->files()->getQuery(), function( Grid $grid) {
                $grid->title(trans('defender.files'));
                $grid->name('Name');
                $grid->path('Download')->downloadable();

                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableCreateButton();
            } );
        });

        $show->relation('options', function(Product $product){

            return new Grid($product->files()->getQuery(), function( Grid $grid) {
                $grid->title(trans('defender.options'));

                $grid->pkey('Key');
                $grid->val('Value');

                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableCreateButton();
            } );
        });

        return $show;
    }

    protected function grid()
    {
        return new Grid( new Product(), function (Grid $grid) {

            $grid->disableCreateButton();
            //$grid->disableRowSelector();
            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id('ID')->sortable();
            $grid->key('Key');
            $grid->column('title','Title')->badge('blue');
            $grid->version('Version');

            //$grid->column('Opts');
            //$grid->column('Files');

            $grid->created_at('Created');
            $grid->updated_at('Updated');

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
            });

            $grid->disableEditButton();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });

            $grid->rows(function ($rows) {
                $rows->map(function (Row $row) {
                    $row->column('Opts', "<a href='poptions?product_id={$row->id}'>Options</a>");
                    $row->column('Files', "<a href='pfiles?product_id={$row->id}'>Files</a>");
                } );
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return new Form(new Product(), function (Form $form) {
            $form->hidden('manager_id')->value(Admin::user()->id);

            $form->display('id', 'ID');

            if ($form->isEditing()) {
                $form->display('key', 'Key');
            } else {
                $form->text('Key', 'Key');
            }

            $form->text('version', 'Version');
            $form->text('title', 'Title');
            $form->textarea('description', 'Description');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
