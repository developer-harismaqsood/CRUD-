<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DirectoryCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'directory:cron';

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
        $Users = User::where('status','approved')->get();

        // $plucked = $User->pluck('order_number');
        // $plucked->all();
        // $dir_create = preg_filter('/^/', 'mkdir ', $plucked->toArray());
        // $dir_name   = implode(" ",$dir_create);

        foreach($Users as $user)
        {
            $directory = 'mkdir '.$user->order_number;
            $process = Process::fromShellCommandline($directory, '/wamp/www/');
            $process->run();

            if (!$process->isSuccessful()) 
            {
                throw new ProcessFailedException($process);
            }   

            echo $process->getOutput();


         User::where('order_number',$user->order_number)->update(['status' => 'wordpress',]);
        }
    }
}
