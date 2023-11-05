<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use App\Server;
use App\Helpers\CronsHelper;

class DomainCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:cron';

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
        $order  =  User::where('status','app-credentials')->first();
        $Server =   Server::first();
        
        if(!$order)
            return false;

        $website    = Customization::where(
                       ['order_id' => $order->order_number, 'meta_name' => 'info'])
                    ->first();
        $extract_info = json_decode($website['meta_value']);


        $search  = array('https://','http://');
        $replace = array('');
        $filter_url = str_replace($search, $replace, $extract_info->url);

        $fields =  array(
                  'server_id' => $Server->server_id, 
                  'app_id'    => $order->server_app_id,
                  'cname'     => $filter_url
               );

        $data = json_encode($fields);   

        $url = 'app/manage/cname'; $type = 'post'; $access_token = CronsHelper::getAccessToken($Server->server_id);
        $response = CronsHelper::ApiRequest($url, $type, $access_token, $data);

        User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'domain',
            ]);

          return $response;
    }
}
