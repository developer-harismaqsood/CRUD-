<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use App\Server;
use App\Helpers\CronsHelper;
use phpseclib3\Net\SSH2;

class PagesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pages:cron';

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
        $order  =   User::where('status','data-import')->first();

        if(!$order) 
            return false;

        $ssh = new SSH2($Server->ip, $order->ssh_port);
        if(!$ssh->login($order->ssh_username, $order->ssh_password)) 
            exit('Login Failed');


           // Pages
        $ssh->exec("cd public_html && wp post delete $(wp post list --post_type='page' --format=ids) --force");
        $ssh->exec("cd public_html && wp option update show_on_front page");
        $ssh->exec("cd public_html && wp option update page_on_front $(wp post create --post_title='Home Page' --post_status='publish' --post_type='page' --page_template='home.php' --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=page)");
        $ssh->exec("cd public_html && wp post create --post_title='About Us' --post_status='publish' --post_type='page' --page_template='about-us.php' && wp post create --post_title='News' --post_status='publish' --post_type='page' --page_template='news.php' && wp post create --post_title='Our Communities' --post_status='publish' --post_type='page' --page_template='communities.php' && wp post create --post_title='Contact Us' --post_status='publish' --post_type='page' --page_template='contact-us.php' && wp post create --post_title='Buyers' --post_content='Coming Soon' --post_status='publish' --post_type='page' && wp post create --post_title='Sellers' --post_content='Coming Soon' --post_status='publish' --post_type='page' && wp post create --post_title='Property' --post_content='Coming Soon' --post_status='publish' --post_type='page'");

        User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'pages',
            ]);

            return "Pages added!";
    }
}
