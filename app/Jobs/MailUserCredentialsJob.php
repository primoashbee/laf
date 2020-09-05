<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use App\Mail\MailUserCredentials;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MailUserCredentialsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Mail::to('test-a76ebrhvs@srv1.mail-tester.com')->send(new MailUserCredentials($this->user));
        Mail::to($this->user->send_to)->send(new MailUserCredentials($this->user));
    }
}
