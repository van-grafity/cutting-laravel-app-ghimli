<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleTransferNoteDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'bundle_transfer_note_id',
        'bundle_transaction_id',
        'ticket_id',
    ];

    public function transferNote()
    {
        return $this->belongsTo(BundleTransferNote::class, 'bundle_transfer_note_id', 'id');
    }

    public function bundleTransaction()
    {
        return $this->belongsTo(BundleStockTransaction::class, 'bundle_transaction_id', 'id');
    }
}
