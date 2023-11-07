<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Payment
 *
 * @property int $payment_id 収支明細ID
 * @property int $summary_ym 集計年月
 * @property int $group_id グループID
 * @property int $member_id メンバーID
 * @property int $category_id 収支分類ID
 * @property int|null $categorized_payment_id 収支分類別明細ID
 * @property string|null $payment_date 発生日付
 * @property int $amount 金額
 * @property string|null $payment_label 収支名目
 * @property int $del_flg 削除フラグ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment paymentsForGroup(int $groupId, array $memberIDs, string $summary_ym)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCategorizedPaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereSummaryYm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
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
    protected $primaryKey = 'payment_id';

    public function scopePaymentsForGroup($query, int $groupId, array $memberIDs, string $summary_ym)
    {
        return Payment::leftJoin('member_category_histories', function ($join) {
                $join
                    ->on('payments.summary_ym', '=', 'member_category_histories.summary_ym')
                    ->on('payments.category_id', '=', 'member_category_histories.category_id')
                    ->on('payments.member_id', '=', 'member_category_histories.member_id');
            })
            ->where('payments.group_id', $groupId)
            ->where('payments.summary_ym', $summary_ym)
            ->where('payments.del_flg', false)
            ->selectRaw('payments.payment_id, payments.summary_ym, payments.member_id, payments.category_id, member_category_histories.category_name, payments.categorized_payment_id, payments.payment_date, payments.amount, payments.payment_label')
            ->orderByRaw('payments.member_id, payments.category_id, payments.categorized_payment_id');
    }
}
