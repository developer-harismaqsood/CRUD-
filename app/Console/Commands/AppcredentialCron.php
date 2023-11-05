<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use App\Server;
use App\Helpers\CronsHelper;
use Illuminate\Support\Str;

class AppcredentialCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appcredential:cron';

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
        $order  =   User::where('status','hosting-account')->first();

        if(!$order)
            return false;

        $username = strtolower('twp'.Str::random(8).$order->server_app_id);
        $password = 'TWp'.rand(10000,99999);

        $fields =  array(
                      'server_id' => $Server->server_id, 
                      'app_id'    => $order->server_app_id, 
                      'username' => $username, 
                      'password'=> $password
                   );

        $data = json_encode($fields);   

        $url = 'app/creds'; $type = 'post'; $access_token = CronsHelper::getAccessToken($Server->server_id);
        $response = CronsHelper::ApiRequest($url, $type, $access_token, $data);

        User::where(['order_number'     => $order->order_number])->update([
                      'app_cred_id'     => $response['app_cred_id'],
                      'ssh_username'    => $username,
                      'ssh_password'    => $password,
                      'status'          => 'app-credentials'
                   ]);

        $fields = array(
                'server_id' => $Server->server_id, 
                'app_id'    => $order->server_app_id, 
                'update_perms_action' =>    'confirmed_enable'
             );

        $data = json_encode($fields);

        $url = 'app/updateAppSshPerms'; $type = 'post'; $access_token = CronsHelper::getAccessToken($Server->server_id);
        $response_sshperms = CronsHelper::ApiRequest($url, $type, $access_token, $data);

        return $response_sshperms;
    }
}
