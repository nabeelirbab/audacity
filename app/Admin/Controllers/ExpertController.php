<?php

namespace App\Admin\Controllers;

use App\Enums\MT4FileType;
use App\Models\Expert;
use App\Models\MT4File;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;


class ExpertController extends AdminController
{

    protected $translation = 'experts';

    protected function detail($id)
    {
        $show = new Show(Expert::findOrFail($id));

        $show->name('Name');
        $show->created_at('Created');
        $show->updated_at('Updated');

        /* $show->templates('Templates', function ($template) {
            $template->resource('/admin/templates');
        }); */

        return $show;
    }

    protected function grid()
    {
        return new Grid(Expert::with('ex4_file'), function (Grid $grid) {
            $grid->id('ID');

            $grid->model()
                ->whereManagerId(Admin::user()->id);

            $grid->column('name');
            $grid->column('ex4_file.name');

            $grid->updated_at('Updated')->display(function ($updated_at) {
                return Carbon::parse($updated_at)->diffForHumans();
            });

            $grid->filter(function ($filter) {
                $filter->like('name');
                $filter->disableIdFilter();
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
        return new Form( new Expert(), function (Form $form) {

            $form->display('id', 'ID');
            $form->hidden('manager_id')->value(Admin::user()->id);

            $form->text('name', 'Name')->required();

            $files = MT4File::whereType(MT4FileType::EX4)->whereManagerId(Admin::user()->id)->pluck('name', 'id');
            $form->select('ex4_file_id', 'Ex4 File')->options($files)->required();

            $form->switch('enabled', 'Enabled?')->default(1);

            $form->textarea('template_default', 'Default Options')->required();

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
