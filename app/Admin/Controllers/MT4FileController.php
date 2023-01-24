<?php

namespace App\Admin\Controllers;

use App\Enums\FileType;
use App\Enums\MT4FileType;
use App\Models\MT4File;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MT4FileController extends AdminController
{

    protected $translation = 'mt4-files';

    protected function grid()
    {
        return new Grid(new MT4File(), function (Grid $grid) {
            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id('ID')->sortable();

            $grid->column('path')->downloadable();
            $grid->column('type')->sortable();

            $grid->created_at();
            $grid->updated_at();

            $grid->filter(function (Filter $filter) {
                $filter->like('name');
                $filter->equal('type')->select(MT4FileType::map());
                $filter->disableIdFilter();
            });
            $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableEdit();
            });
            $grid->rows(function ($row) {
            });
        });
    }

    protected function form()
    {
        return new Form(new MT4File(), function (Form $form) {

            $form->hidden('manager_id')->value(Admin::user()->id);
            $form->hidden('name');
            $form->hidden('data');
            $form->display('id', 'ID');

            if ($form->isEditing()) {
                $form->display('name', 'Name');
            }

            $form->file('path')->required()->autoUpload();

            $form->radio('type')->options(MT4FileType::map())->default(MT4FileType::EX4->value);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function ($form) {
                if (!is_null($form->path)) {
                    $storage = Storage::disk(config('admin.upload.disk'));
                    $data = $storage->get($form->path);
                    $name = Str::of($form->path)->basename();

                    $form->name = $name;
                    $form->data = $data;
                    $form->is_updated_or_new = 1;
                }
            });

        });
    }

}
