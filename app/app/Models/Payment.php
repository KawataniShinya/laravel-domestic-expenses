<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_ym',
        'group_id',
        'member_id',
        'category_id',
        'income_flg',
        'categorized_payment_id',
        'payment_date',
        'amount',
        'payment_label',
        'del_flg'
    ];
}
