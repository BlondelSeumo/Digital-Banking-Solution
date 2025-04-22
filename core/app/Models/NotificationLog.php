<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use ApiQuery;

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public static function notificationTypes(){
        return [
            'email',
            'sms',
            'push'
        ];
    }

    function scopeReportQuery($query) {
        return $query->selectRaw('notification_logs.*,
            CASE WHEN notification_logs.user_id = 0 THEN "N/A" ELSE users.account_number END as account_number,
            CASE WHEN notification_logs.user_id = 0 THEN "N/A" ELSE users.username END AS username'
        )
        ->leftJoin('users', 'notification_logs.user_id', '=', 'users.id')
        ->searchable(['username', 'account_number', 'subject'])
        ->filterable()
        ->orderable()
        ->dynamicPaginate();
    }

}
