<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CronsHelper;
use App\Customization;
use App\User;
use App\Server;

class ServerappCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serverapp:cron';

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
        $order   =   User::where('status','approved')->first();

        if($order)
        {    
            $Server     = Server::first();
            $website    = Customization::where(
                                       ['order_id' => $order->order_number, 'meta_name' => 'info'])
                                    ->first();
        
            $extract_info = json_decode($website['meta_value']);
            $fields = array(
                        'server_id'    => $Server->server_id, 
                        'application'  => 'WordPress', 
                        'app_version'  => '5.8', 
                        'app_label'    => $extract_info->startup->project_name, 
                        'project_name' => 'Thrilliant Websites'
                    );
            $data = json_encode($fields);

            $url = 'app'; $type = 'post'; $access_token = CronsHelper::getAccessToken($Server->server_id);

            $response = CronsHelper::ApiRequest($url, $type, $access_token, $data);

            // save opertaion_id and get to save server_application_id
            if($response)
            {
               $operation_response = CronsHelper::ApiRequest('operation/'.$response['operation_id'], 'get', 
                  CronsHelper::getAccessToken($Server->server_id), null);

               $app_id = $operation_response['operation']['app_id'];

               User::where(['order_number' => $order->order_number])->update([
                  'operation_id'      => $response['operation_id'],
                  'status'            => 'in-process',
                  'server_app_id'      => $app_id,
               ]);
               
               return $app_id;
            }
        }
        else
        {
            return false;
        }   
        
    }
}
