<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CronsHelper;
use App\Customization;
use App\User;
use App\Server;

class HostingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hosting:cron';

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
      $order  =  User::where('status','in-process')->first();
      $Server =  Server::first();
      
      if(!$order)
         return false;

      $url = 'server'; $type = 'get'; $access_token = CronsHelper::getAccessToken($Server->server_id);
      $response = CronsHelper::ApiRequest($url, $type, $access_token, null);

      if($response!=null){
        if($response['status']==true)
        {
           $apps=$response['servers'][0]['apps'];
           foreach ($apps as $app) 
           {
               if( $app['id'] == $order->server_app_id )
               {
                  $wp_username = $app['app_user'];
                  $wp_password = $app['app_password'];
                  $db_name = $app['mysql_db_name'];
                  $db_username = $app['mysql_user'];
                  $db_password = $app['mysql_password'];
                  $db_host = 'localhost';
                  $demo_url = $app['app_fqdn'];
                  $sys_user = $app['sys_user'];
                  $sys_password = $app['sys_password'];
                  $ssh_port = 22;

                  User::where(['order_number' => $order->order_number])->update([
                         'status'       => 'hosting-account',
                         'wp_username'  => $wp_username,
                         'wp_password'  => $wp_password,
                         'db_name'      => $db_name,
                         'db_username'  => $db_username,
                         'db_password'  => $db_password,
                         'db_host'      => $db_host,
                         'demo_url'     => $demo_url,
                         'sys_user'     => $sys_user,
                         'sys_password' => $sys_password,
                         'ssh_port'     => $ssh_port
                  ]);
               }
            }

           }
        }

      // return $response;
    }
}
