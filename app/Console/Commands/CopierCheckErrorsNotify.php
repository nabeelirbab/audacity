<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Models\Order;
use App\Models\User;
use App\Notifications\CopierHasErrors;

class CopierCheckErrorsNotify extends BaseCommand
{
    protected $signature = 'copier:check_errors';

    protected $description = 'Notify manager about errors';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            User::with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('slug', 'manager_copier');
                })
                ->get()->each(function (User $user) {
                    $countErrors = Order::getCountErrors($user->id);
                    if($countErrors > 0) {
                        $user->notify(new CopierHasErrors($countErrors));
                    }
                });

        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            echo $e->getMessage();
        }
    }
}
