<?php

namespace App\Jobs\Auth;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DeleteExpiredToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::table('password_reset_tokens')
            ->where('created_at', '<=', Carbon::now()->subHours(config('auth.passwords.users.expire')))
            ->delete();

        DB::table('invitations')
            ->where('created_at', '<=', Carbon::now()->subHours(config('auth.passwords.users.expire')))
            ->delete();
    }
}
