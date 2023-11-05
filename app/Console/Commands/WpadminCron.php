<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use App\Server;
use App\Helpers\CronsHelper;
use phpseclib3\Net\SSH2;
use Mail;

class WpadminCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wpadmin:cron';

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
            $Server =   Server::first();
         $order  =   User::where('status','menu')->first();
         if(!$order) 
            return false;

         $ssh = new SSH2($Server->ip, $order->ssh_port);
         if(!$ssh->login($order->ssh_username, $order->ssh_password)) 
            exit('Login Failed');


         $website_info = Customization::where(
                     ['order_id' => $order->order_number, 'meta_name' => 'info'])
                  ->first();
         $extract_info = json_decode($website_info['meta_value']);

         $domain = $extract_info->url;
         $title = $extract_info->startup->project_name;

         $username = $order->wp_username;
         $name     = $order->name;
         $password = $order->wp_password;

         $ssh->exec("cd public_html && wp user create '".$name."' '".$username."' --user_pass=".$password." --role=administrator ");
          
        User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'admin-user',
            ]);
        
        $details = [
            'title' => 'Website Created Your credentials here',
            'body'  => 'Wp Username:' . $order->wp_username . 'Wp password:' . $order->wp_password
        ];
       
        \Mail::to($order->email)->send(new \App\Mail\VerficationMail($details));

        User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'live',
            ]);

         return $title." User added!";
    }
}
