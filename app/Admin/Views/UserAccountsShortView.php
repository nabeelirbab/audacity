<?php

namespace App\Admin\Views;

use App\Models\Account;
use Carbon\Carbon;
use Dcat\Admin\Support\LazyRenderable;
use Dcat\Admin\Widgets\Table;

class UserAccountsShortView extends LazyRenderable
{

    public function __construct($userId = null, array $payload = []) {

        $this->payload(['user_id' => $userId]);
        parent::__construct($payload);
    }

    public function render()
    {
        $data = [];

        $accounts = Account::whereUserId($this->user_id)->get([
            'id','account_number', 'title', 'broker_server_name', 'account_status', 'updated_at'
        ]);


        foreach($accounts as $account) {
            /** @var Account $account */
            $url = admin_url('account-analysis/'.$account->account_number);

            $data[] = [
                '<a href="'.admin_url('accounts').'/'.$account->id.'" >'.$account->account_number.'</a>',
                $account->title,
                $account->broker_server_name,
                $account->account_status->label(),
                $account->updated_at->diffForHumans(),
                '<a href="'.$url.'" ><i class="fa fa-signal"></i></a>'
            ];
        }

        return Table::make(['Login', 'Title', 'Broker','Status','Updated','Analysis'], $data);
    }
}
