<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SetupCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     *
     * @return int
     */
    public function handle()
    {
        $Users = User::where('status','wordpress')->get();
        
        foreach($Users as $user)
        {
            $dir_name = '/wamp/www/'.$user->order_number;
            $process = Process::fromShellCommandline('wp core download', $dir_name);
            $process->run();

            $process = Process::fromShellCommandline('SET PATH=%PATH%;C:\wamp\bin\mysql\mysql5.7.24\bin', $dir_name);
            $process->run();

            if (!$process->isSuccessful()) 
            {
                throw new ProcessFailedException($process);
            }   

            echo $process->getOutput();

            User::where('order_number',$user->order_number)->update(['status' => 'theme',]);
        }

        echo "setup completed";
    }
}
