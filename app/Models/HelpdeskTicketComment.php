<?php

namespace App\Models;

use App\Models\HelpdeskTicket;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     title="HelpdeskTicketComment",
 *     description="HelpdeskTicketComment model",
 *     @OA\Xml(
 *         name="HelpdeskTicketComment"
 *     ),
 * @OA\Property(
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * )
 * )
*/
class HelpdeskTicketComment extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'helpdesk_ticket_comments';

    protected $fillable = [
        'author_id', 'ticket_id', 'body'
    ];

    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticket() : BelongsTo
    {
        return $this->belongsTo(HelpdeskTicket::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function(HelpdeskTicketComment $comment) {
            $comment->ticket()->rawUpdate(
                [
                    'updated_at' => $comment->freshTimestampString(),
                    'last_commentator_id' => $comment->author_id,
                ]
                );
        });

    }

}