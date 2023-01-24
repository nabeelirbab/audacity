<?php

namespace App\Admin\Controllers;

use App\Models\VideoPost;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class VideoPostController extends AdminController
{
    protected function grid()
    {
        return new Grid( new VideoPost(), function (Grid $grid) {

            $grid->model()->where('manager_id', Admin::id());

            $grid->column('title');
            $grid->column('type')->display(function($type) {
                return $type == 1 ? 'Youtube' : 'Vimeo';
            });
            $grid->column('video_id')->display(function($id) {
                switch($this->type) {
                    case 1:
                        return '<iframe src="https://www.youtube.com/embed/'.$id.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                    case 2:
                        return '<iframe src="https://player.vimeo.com/video/'.$id.'" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                }

                return $id;
            });
            $grid->column('is_public')->switch();

            $grid->disableFilterButton();
            $grid->disableRefreshButton();
            $grid->disableViewButton();
        });
    }

    protected function form()
    {
        return new Form( new VideoPost(), function (Form $form) {
            $form->hidden('manager_id')->value(Admin::user()->id);

            $form->text('title')->required();
            $form->textarea('description')->required();
            $form->switch('is_public')->default(1);
            $options = ['1'=> 'Youtube', '2' => 'Vimeo' ];
            $form->radio('type')->options($options)->default(1);
            $form->text('video_id')->required();

            $form->disableViewButton();
        });
    }
}