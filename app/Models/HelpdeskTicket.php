<?php

namespace App\Models;

use App\Enums\HelpdeskTicketPriority;
use App\Enums\HelpdeskTicketStatus;
use App\Models\HelpdeskTicketComment;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     title="HelpdeskTicket",
 *     description="HelpdeskTicket model",
 *     @OA\Xml(
 *         name="HelpdeskTicket"
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
class HelpdeskTicket extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'helpdesk_tickets';

    protected $casts = [
        'status' => HelpdeskTicketStatus::class,
        'priority' => HelpdeskTicketPriority::class
    ];

    protected $fillable = [
        'author_id', 'description', 'subject', 'status', 'priority',
        'regarding_type', 'regarding_id', 'last_commentator_id', 'read_at'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function last_commentator()
    {
        return $this->belongsTo(User::class, 'last_commentator_id');
    }

    public function comments()
    {
        return $this->hasMany(HelpdeskTicketComment::class, 'ticket_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function scopeOpen($query) {
        $query->where('status', HelpdeskTicketStatus::OPEN);
    }

    public function scopeClosed($query) {
        $query->where('status', HelpdeskTicketStatus::CLOSED);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->status = HelpdeskTicketStatus::OPEN;
            $query->read_at = $query->freshTimestamp();
        });
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Mark the notification as unread.
     *
     * @return void
     */
    public function markAsUnread()
    {
        if (! is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }


}
