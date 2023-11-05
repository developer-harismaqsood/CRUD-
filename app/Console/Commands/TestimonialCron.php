<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CronsHelper;
use App\Customization;
use App\User;
use App\Server;
use phpseclib3\Net\SSH2;

class TestimonialCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testimonial:cron';

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
        $order  =   User::where('status','dynamic-pages')->first();

         if(!$order) 
            return false;

         $ssh = new SSH2($Server->ip, $order->ssh_port);
         if(!$ssh->login($order->ssh_username, $order->ssh_password)) 
            exit('Login Failed');

         $website = Customization::where(
                     ['order_id' => $order->order_number, 'meta_name' => 'info'])
                  ->first();
         $extract_info = json_decode($website['meta_value']);

         $domain = $extract_info->url;
         $title = $extract_info->startup->project_name;

         $testimonials_show = $extract_info->testimonial_status;

        /// Testimonials
        if($testimonials_show) 
        {

         $website_testimonial = Customization::where(
                     ['order_id' => $order->order_number, 'meta_name' => 'testimonial'])
                  ->first();

         $extract_testimonials = json_decode($website_testimonial['meta_value']);

          $ssh->exec("cd public_html && wp post delete $(wp post list --post_type='testimonials' --format=ids) --force");

          foreach ($extract_testimonials as $testimonial) {
            $ssh->exec("cd public_html && wp post create --post_title='".$testimonial->title."' --post_content='".$testimonial->description."' --post_status='publish' --post_type='testimonials'");

          }
        }
              /// banners
              // $banners = WebsiteBanner::find()->andWhere(['website_id' => $Website->id, 'status' => 1, 'trashed' => 0])->all();

              if($extract_info)
              {
                 $ssh->exec("cd public_html && wp post delete $(wp post list --post_type='slideshow' --format=ids) --force");
                  $i = 1;
                  foreach ($extract_info->cover as $cover) {
                    $i++;
                    $title = "Banner - ".$i;
                    $image_path = $ASSET_URL.$cover->name; 
                    // if($banner->type_id==1)
                    // {
                        $ssh->exec("cd public_html && wp media import ".$image_path." --post_id=$(wp post create --post_title='".$title."' --post_status='publish' --post_type='slideshow' --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=slideshow) --title='".$title."' --featured_image");
                    // }

                    // if($banner->type_id==3){
                    //   $ssh->exec("cd public_html && wp media import ".$banner->live_image." --post_id=$(wp post create --post_title='".$title."' --post_status='publish' --post_type='slideshow' --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=slideshow) --title='".$title."' --featured_image");

                    // }

                    // if($banner->video_url!=null && $banner->type_id==2)
                    // {
                    //   $video=$banner->video_url;
                    //   $ssh->exec("cd public_html && wp post create --post_title='".$title."' --post_status='publish' --post_type='slideshow' --meta_input='{\"slideshow_video_url\": \"".$video."\"}' ");
                    // }

                    }
              }


            User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'testimonial',
            ]);

              return $title." testimonial and header images added!";
    }
}
