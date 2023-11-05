<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CronsHelper;
use App\Customization;
use App\User;
use App\Server;
use phpseclib3\Net\SSH2;

class MenuCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:cron';

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
         $order  =   User::where('status','communities')->first();

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

          $ssh->exec("cd public_html && wp menu delete header && wp menu delete footer ");
          $ssh->exec("cd public_html && wp menu create header && wp menu location assign header main-menu && wp menu create Footer && wp menu location assign footer footer-menu ");
          //wp menu item add-custom header 'Home' '".$domain."' &&
          $ssh->exec("cd public_html && wp menu item add-custom header 'About Us' '".$domain."/about-us' && wp menu item add-custom header 'Property' '".$domain."/property' && wp menu item add-custom header 'Communities' '".$domain."/our-communities' && wp menu item add-custom header 'Buyers' '".$domain."/buyers' && wp menu item add-custom header 'Sellers' '".$domain."/sellers' && wp menu item add-custom header 'Blog' '".$domain."/news' && wp menu item add-custom header 'Contact Us' '".$domain."/contact-us' && wp menu item add-custom footer 'Home' '".$domain."' && wp menu item add-custom footer 'About Us' '".$domain."/about-us' && wp menu item add-custom footer 'Property' '".$domain."/property' && wp menu item add-custom footer 'Communities' '".$domain."/our-communities' && wp menu item add-custom footer 'Buyers' '".$domain."/buyers' && wp menu item add-custom footer 'Sellers' '".$domain."/sellers' && wp menu item add-custom footer 'Blog' '".$domain."/news' && wp menu item add-custom footer 'Contact Us' '".$domain."/contact-us' ");
            
          User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'menu',
            ]);

          return $title." Menu added!";
    }
}
