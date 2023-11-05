<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CronsHelper;
use App\Customization;
use App\User;
use App\Server;
use phpseclib3\Net\SSH2;

class CommunityCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'community:cron';

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

          $ASSET_URL = 'https://stagefalcon.com/thrilliant_v2/public/order_assets/';

       $Server =   Server::first();
      $order  =   User::where('status','testimonial')->first();

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

          // $ssh->exec("cd public_html && wp post delete $(wp post list --post_type='communities' --format=ids) --force");

         $website_community = Customization::where(
                     ['order_id' => $order->order_number, 'meta_name' => 'communities'])
                  ->first();
         $extract_communities = json_decode($website_community['meta_value']);


          foreach ($extract_communities as $Community) 
          {

            $image = $ASSET_URL.$Community->imageName;
            $ssh->exec("cd public_html && wp media import ".$image." --post_id=$(wp post create --post_title='".$Community->title."' --post_content='".$Community->description."' --post_status='publish' --post_type='communities' --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=communities) --title='".$Community->title."' --featured_image");
          }

           User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'communities',
            ]);

          return $title." Communities added!";
    }
}
