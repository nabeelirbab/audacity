<?php

namespace App\Admin\Views;

use App\Models\HelpdeskTicketComment;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Callout;
use Dcat\Admin\Widgets\Markdown;
use Illuminate\Contracts\Support\Renderable;

class HelpdeskCommentView implements Renderable
{

    private HelpdeskTicketComment $comment;

    public function __construct(HelpdeskTicketComment $comment)
    {
        $this->comment = $comment;
    }

    public function render() {

        return tap(new Row, function ($row) {
            $title = '<b>'.$this->comment->author->name.'</b> commented at '. $this->comment->created_at;
            $box = new Callout(new Markdown($this->comment->body), $title);
            $box->collapsable();

            $ava = "<img class='round' src='{$this->comment->author->getAvatar()}' alt='avatar' height='40' width='40' />";
            $row->column(1, $ava);
            $row->column(11, $box);
        })->render();
    }

}
