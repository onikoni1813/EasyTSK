<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'status',
        'proof_text',
        'proof_email',
        'proof_password',
        'proof_image',
        'proof_hash',
        'admin_notes',
        'moderated_by',
        'moderated_at',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
}
