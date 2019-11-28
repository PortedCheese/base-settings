<?php

namespace PortedCheese\BaseSettings\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use PortedCheese\BaseSettings\Models\LoginLink;

class GenerateLoginLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:login-link {email} {--send=} {--get}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate link for single login to site';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument("email");
        $send = $this->option("send");
        if ($this->option("get")) {
            $send = null;
        }
        elseif (empty($send)) {
            $send = $email;
        }
        try {
            User::query()
                ->where("email", $email)
                ->firstOrFail();
            $link = LoginLink::create([
                "email" => $email,
                "send" => $send,
            ]);
            $url = route('profile.auth.email-authenticate',['token' => $link->token]);
            $this->info("Link generated $url $send");
        }
        catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
