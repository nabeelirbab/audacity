<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\ClearSystemLogButton;
use App\Admin\Views\SystemLogContextView;
use App\Enums\SystemLogLevel;
use App\Models\SystemLog;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class SystemLogController extends AdminController
{

    protected function grid()
    {
        return new Grid( new SystemLog(), function (Grid $grid) {

            $grid->model()->orderby('datetime', 'desc');
            $grid->tools(function (Grid\Tools $tools) {
                $tools->append(new ClearSystemLogButton(___('clear')));
            });

            $grid->selector(function(Grid\Tools\Selector $selector) {
                $levelCounts = SystemLog::levelCounts(SystemLogLevel::values());

                $levels = collect(SystemLogLevel::map())->mapWithKeys(function($label, $level) use($levelCounts) {
                    $count = isset($levelCounts[$level]) ? '('.$levelCounts[$level].')' : '';
                    return [
                        $level => $label.$count
                    ];
                });

                $selector->select('level', $levels->toArray(), function($query, $value) {
                    $query->whereIn('level', $value);
                });
            });

            $grid->column('datetime')->dateHuman()->width('150');

            $grid->column('level')->enumColored();

            $grid->column('message')->expand(function() {
                return SystemLogContextView::make($this->id);
            });;

            $grid->quickSearch(['message', 'context']);
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->disableCreateButton();
        });
    }

    protected function form()
    {
        return new Form( new SystemLog(), function (Form $form) {
        });
    }

}