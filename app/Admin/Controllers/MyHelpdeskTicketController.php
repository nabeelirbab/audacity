<?php

namespace App\Admin\Controllers;

use App\Admin\Views\HelpdeskCommentView;
use App\Enums\HelpdeskTicketPriority;
use App\Enums\HelpdeskTicketStatus;
use App\Models\HelpdeskTicket;
use App\Models\HelpdeskTicketComment;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;

class MyHelpdeskTicketController extends AdminController
{

    protected $translation = 'helpdesk';

    protected function grid()
    {
        return new Grid( HelpdeskTicket::with(['last_commentator:id,name']), function (Grid $grid) {

            $model = $grid->model()->whereAuthorId(Admin::user()->id)->orderBy('priority', 'desc');

            if(!request()->exists('_selector')) {
                $model->whereStatus(HelpdeskTicketStatus::OPEN);
            }

            $grid->selector(function(Grid\Tools\Selector $selector) {
                $selector->selectOne('status', ___('status'),HelpdeskTicketStatus::map(), function($query, $value) {
                    $query->where('status', $value);
                });
            });

            $grid->column('read_at', ' ')->display(function ($date) use($grid) {
                if(!$date) {
                    $url = $grid->resource().'/' . $this->id.'/comment';
                    return "<a href='$url'><i class='feather icon-bell'></i></a>";
                }

                return '';
            });

            $grid->column('subject')->link(function() use($grid) {
                return $grid->resource().'/' . $this->id.'/comment';
            },'');

            $grid->column('created_at')->dateHuman();
            $grid->column('updated_at')->dateHuman()->sortable();

            $grid->column('last_commentator.name',___('updated_by'));

            $grid->quickSearch(['subject']);
            $grid->disableFilterButton();
            $grid->disableRowSelector();
            $grid->disableActions();
        });
    }

    public function create(Content $content)
    {
        return $content
            ->translation($this->translation)
            ->title(___('labels.title_create'))
            ->description(___('labels.description_create'))
            ->body($this->form());
    }

    public function commentShow($ticketId, Content $content)
    {
        $ticket = HelpdeskTicket::with(['comments', 'comments.author'])->findOrFail($ticketId);

        $ticket->markAsRead();

        return $content
            ->translation($this->translation)
            ->title($ticket->subject)
            ->description($ticket->created_at)
            ->body($this->formComment($ticket));
    }

    public function commentStore($ticketId)
    {
        $ticket = HelpdeskTicket::with(['comments', 'comments.author'])->findOrFail($ticketId);

        return $this->formComment($ticket)->store();
    }

    protected function formComment($ticket) {

        return new Form( new HelpdeskTicketComment(), function (Form $form) use($ticket) {

            $form->action(request()->fullUrl());
            foreach($ticket->comments as $comment) {
                $form->row(function (Form\Row $row) use($comment) {
                    $row->html((new HelpdeskCommentView($comment))->render());
                });
            }

            $form->hidden('ticket_id')->value($ticket->id);
            $form->hidden('author_id')->value(Admin::user()->id);
            $form->row(function (Form\Row $form) {
                $form->width(12)->textarea('body', ___('comment'))->required();
            });

            $form->disableDeleteButton();
            $form->disableViewButton();
            $form->disableResetButton();
            $form->disableHeader();

            $form->saved(function (Form $form) {
                return $form->response()->success(__('admin.save_succeeded'))->redirect($form->resource(-2));
            });
        });
    }

    protected function form()
    {
        return new Form( new HelpdeskTicket, function (Form $form) {

            $form->hidden('manager_id')->value(Admin::user()->manager_id);
            $form->hidden('author_id')->value(Admin::user()->id);
            $form->text('subject')->required();
            $form->textarea('description')
                ->required()
                ->placeholder(___('placeholder_description'));

            $form->saved(function (Form $form) {
                /** @var HelpdeskTicket $model */
                $model = $form->repository()->model();
                $inputs = $form->input();
                $inputs = array_merge($inputs, ['body' => $inputs['description'] ]);

                $model->comments()->create($inputs);
            });

            $form->disableDeleteButton();
            $form->disableViewButton();
            $form->disableResetButton();
            $form->disableHeader();
        });
    }
}