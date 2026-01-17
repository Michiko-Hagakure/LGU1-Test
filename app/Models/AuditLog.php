<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',    
        'action',     
        'model',      
        'model_id',   
        'changes',    
        'ip_address', 
        'user_agent', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}