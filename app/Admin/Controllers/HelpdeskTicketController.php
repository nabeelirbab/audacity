<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\MarkTicketAsClosedBatchAction;
use App\Admin\RowActions\MarkAsClosed;
use App\Admin\RowActions\MarkAsOpen;
use App\Admin\Views\HelpdeskCommentView;
use App\Enums\HelpdeskTicketPriority;
use App\Enums\HelpdeskTicketStatus;
use App\Models\HelpdeskTicket;
use App\Models\HelpdeskTicketComment;
use App\Models\Tag;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Displayers\Actions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;

class HelpdeskTicketController extends AdminController
{
    protected function grid()
    {
        return new Grid( HelpdeskTicket::with(['author','last_commentator:id,name']), function (Grid $grid) {

            $grid->model()->whereManagerId(Admin::user()->id)->orderBy('priority', 'desc');

            $grid->selector(function(Grid\Tools\Selector $selector) {
                $selector->selectOne('status', ___('status'), HelpdeskTicketStatus::map(), function($query, $value) {
                    $query->where('status', $value);
                });
            });

            $grid->column('last_commentator_id', ' ')->display(function ($commentor_id) use($grid) {
                if($commentor_id != $this->manager_id && $this->status != HelpdeskTicketStatus::CLOSED) {
                    $url = $grid->resource().'/' . $this->id.'/comment';
                    return "<a href='$url'><i class='feather icon-bell'></i></a>";
                }

                return '';
            });

            $grid->column('priority')->enumColored();

            $grid->column('subject')->link(function() use($grid) {
                return $grid->resource().'/' . $this->id.'/comment';
            },'');

            $grid->column('author.name', ___('user'))->link(function() {
                return admin_route('clients.index') . '/' . $this->author_id;
            },'')->filter();

            $grid->column('created_at')->dateHuman();
            $grid->column('updated_at')->dateHuman()->sortable();

            $grid->column('last_commentator.name',___('updated_by'));

            $grid->actions(function(Actions $actions) {
                if($actions->row->status == HelpdeskTicketStatus::OPEN->label()) {
                    $actions->prepend(new MarkAsClosed());
                }

                if($actions->row->status == HelpdeskTicketStatus::CLOSED->label()) {
                    $actions->prepend(new MarkAsOpen());
                }
            });

            $grid->tools(function ($tools) {

                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->divider();
                    $batch->add(new MarkTicketAsClosedBatchAction(___('close')));
                });
            });

            $grid->quickSearch(['subject', 'author.name']);
            $grid->disableFilterButton();
            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            //$grid->disableActions();
            $grid->disableCreateButton();
        });
    }

    public function commentShow($ticketId, Content $content)
    {
        $ticket = HelpdeskTicket::with(['comments', 'comments.author'])->findOrFail($ticketId);

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
                $form->width(12)->markdown('body', ___('comment'))->required();
            });

            $form->disableDeleteButton();
            $form->disableViewButton();
            $form->disableResetButton();
            $form->disableHeader();

            $form->saved(function (Form $form) use($ticket) {
                $ticket->markAsUnread();
                return $form->response()->success(__('admin.save_succeeded'))->redirect($form->resource(-2));
            });
        });
    }

}