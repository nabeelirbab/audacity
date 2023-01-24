<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\LanguageLineForm;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Actions\QuickEdit;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Spatie\TranslationLoader\LanguageLine;

class TranslatorController extends AdminController
{

    public function index(Content $content)
    {
        $group = request()->get('group');
        $groups = LanguageLine::groups();

        if( empty($group)) {
            if($groups->count() == 0) {
                return $content
                    ->header(___('no_group'))
                    ->description(___('add_group'));
            }
            $group = $groups->first();
        }

        return $content->body($this->grid($group, $groups));
    }

    public function create(Content $content)
    {
        $group = request()->get('group');

        return $content
            ->header($this->title())
            ->body($this->form($group));
    }

    public function edit($id, Content $content)
    {
        $group = request()->get('group');

        return $content
            ->header($this->title())
            ->body($this->form($group)->edit($id));
    }

    protected function grid($currentGroup, Collection $groups)
    {
        return new Grid( new LanguageLine(), function (Grid $grid) use($groups, $currentGroup) {

            $grid->wrap(function (View $view) use($groups, $currentGroup) {

                $tabs = new Tab();
                $tabs->vertical();

                foreach ($groups as $group) {
                    if ($currentGroup == $group)
                        $tabs->add($currentGroup, $view, true);
                    else
                        $tabs->addLink($group, url()->current() . '?group=' . $group);
                }

                return $tabs;
            });

            $grid->model()->whereGroup($currentGroup);

            $grid->column('key')->display(function ($_, $column) use($grid) {

                $action = new QuickEdit($this->key);

                return $action
                    ->setGrid($grid)
                    ->setColumn($column)
                    ->setRow($this);
            });

            $locales = LanguageLine::getSupportedLocales();

            if(count($locales) > 1) {
                foreach($locales as $locale) {
                    $grid->column($locale)->editable();
                }

                $grid->combine('locales', $locales, ___('locales'));
            } else {
                if(count($locales) == 0)
                    admin_toastr(___('empty_supported_locales'));
                else
                    $grid->column($locales[0])->editable();
            }

            $grid->updated_at;

            array_push($locales, 'key' );
            $grid->quickSearch($locales);

            $grid->disableFilterButton();
            $grid->disableRefreshButton();
            $grid->disableRowSelector();
            $grid->disableActions();
            $grid->disableCreateButton();
        });
    }

    protected function form($group = null)
    {
        return new Form( new LanguageLine(), function (Form $form) use($group) {
            $form->display('key');
            $form->display('group');

            foreach(LanguageLine::getSupportedLocales() as $locale) {
                $form->textarea($locale)->required()->rows(2);
            }

            $form->disableViewButton();
            $form->disableDeleteButton();
        });
    }
}