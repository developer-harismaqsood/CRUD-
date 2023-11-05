<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use App\Server;
use App\Helpers\CronsHelper;
use phpseclib3\Net\SSH2;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DataimportCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataimport:cron';

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
        $order  =  User::where('status','theme')->first();

         if(!$order) 
            return false;

         $ssh = new SSH2($Server->ip, $order->ssh_port);
         if(!$ssh->login($order->ssh_username, $order->ssh_password)) 
            exit('Login Failed');

         $website = Customization::where(
                     ['order_id' => $order->order_number, 'meta_name' => 'info'])
                  ->first();
         $extract_info = json_decode($website['meta_value']);


        if( $extract_info->cart_theme == 'premiere' )
            $theme_settings_name  =  'theme_mods_thrilliant_premiere';
        if( $extract_info->cart_theme == 'dynasty' )
            $theme_settings_name  =  'theme_mods_thrilliant_dynasty';
        if( $extract_info->cart_theme == 'regent' )
            $theme_settings_name  =  'theme_mods_thrilliant_regent';

        $template_color_name  = $extract_info->color;

        $website_title  =  $extract_info->startup->project_name;
        $logo_image     =  $extract_info->logo->name ? $extract_info->logo->name : '';


         $hero_1 = $extract_info->text1;
         $hero_2 = $extract_info->text2;
         $hero_3 = $extract_info->text3;

        $domain = $extract_info->url;

        $instagram_feed = 0;
        $facebook_feed  = 1;

        $agent_section = Customization::where(
                     ['order_id' => $order->order_number, 'meta_name' => 'agent'])
                  ->first();
      
        $extract_agent = json_decode($agent_section['meta_value']);

        $agent_title    =  isset($extract_agent->business_name) ? $extract_agent->business_name : '';
        $agent_name     =  isset($extract_agent->agent_name)    ? $extract_agent->agent_name : '';
        $agent_position =  'no position found';
        $agent_address  =  isset($extract_agent->address)  ? $extract_agent->address : '';
        $agent_email    =  isset($extract_agent->email)    ? $extract_agent->email   : '';
        $agent_phone    =  isset($extract_agent->phone)    ? $extract_agent->phone   : '';
        $agent_profile  =  isset($extract_agent->profile)  ? $extract_agent->profile : '';
        $company_name   =  isset($extract_agent->business_name) ? $extract_agent->business_name : '';
        $agent_video    =  isset($extract_agent->profile_video) ? $extract_agent->profile_video : '';
        $agent_image    =  isset($extract_agent->headshotName)  ? $extract_agent->headshotName : '';

        $testimonials_show = $extract_info->testimonial_status;


        $blogs_feed_prodvider = $extract_info->blog->publication_name;
        $blogs_show  =  $extract_info->blog->status;
        $blogs_own   =  $extract_info->blog->another_publication;

        $social_section = Customization::where(
                     ['order_id' => $order->order_number, 'meta_name' => 'social'])
                  ->first();
           $extract_social = json_decode($social_section['meta_value']);



        $facebook  = isset($extract_social->facebook) ? $extract_social->facebook : '';
        $twitter   = isset($extract_social->twitter) ? $extract_social->twitter : '';
        $instagram = isset($extract_social->instagram) ? $extract_social->instagram : '';
        $linkedin  = isset($extract_social->linkedin) ? $extract_social->linkedin : '';
        $pinterest = isset($extract_social->pinterest) ? $extract_social->pinterest : '';
        $youtube   = isset($extract_social->youtube) ? $extract_social->youtube : '';
        $yelp      = isset($extract_social->yelp) ? $extract_social->yelp : '';


         // Logo Image
        $logo_id='';
        if($logo_image)
        {
          $logo_url = $ASSET_URL.$logo_image;
          $logo_id  = $ssh->exec("cd public_html && wp media import ".$logo_url." --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=attachment");
        }
        $logo_id = str_replace('\n', '', trim($logo_id));




        // Agent headshot
        $agent_image_id='';
        if($agent_image)
        {
          $agent_image_url = $ASSET_URL.$agent_image;
          $agent_image_id  = $ssh->exec("cd public_html && wp media import ".$agent_image_url." --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=attachment");
        }
        $agent_image_id  = str_replace('\n', '', trim($agent_image_id));

 
        $contact_form_id = $ssh->exec("cd public_html && wp post list --post_type=wpcf7_contact_form --allow-root --field=ID");

        // $video_title='';
        // $video_link='';
        // $video_image_id='';
        // $video_description='';
        // $WebsiteVideoOfTheMonth=WebsiteVideoOfTheMonth::find()->where(['website_id'=>$website_id])->one();
        // if($WebsiteVideoOfTheMonth!=null){

        //   $video_title=$WebsiteVideoOfTheMonth->title;
        //   $video_link=$WebsiteVideoOfTheMonth->video_link;
        //   $video_image=$WebsiteVideoOfTheMonth->image;
        //   $video_description=$WebsiteVideoOfTheMonth->description;
        //   if($video_image!=null){
        //     $video_image_url=Yii::$app->params['siteUrl'].'uploads/video/'.$video_image;
        //     $video_image_id=$ssh->exec("cd public_html && wp media import ".$video_image_url." --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=attachment");
        //   }
        //   $video_image_id=str_replace('\n', '', trim($video_image_id));

        // }

        // $partner_heading='';
        // $partner_description='';
        // $WebsitePartnersInfo=WebsitePartnersInfo::find()->where(['website_id'=>$website_id])->one();
        // if($WebsitePartnersInfo!=null){
        //   $partner_heading=$WebsitePartnersInfo->title;
        //   $partner_description=$WebsitePartnersInfo->description;
        // }

        $theme_settings=array(
            "custom_css_post_id" => 1,
            "title_tagline_logo" => '{"layout":"'.($logo_image? 'image' : 'text').'","image":"'.$logo_id.'","title":"'.$website_title.'", "subtitle": ""}',
            "thrilliant-quick-search-hide-section" => 0,
            "thrilliant-quick-search-heading" => $hero_3,
            "thrilliant-quick-search-subheading" => "Quick search",
            "thrilliant-quick-search-textheading" => $hero_1,
            "thrilliant-quick-search-heading-regular" => $hero_2,
            "thrilliant-welcome-hide-section" => 0,
            "thrilliant-welcome-photo" => $agent_image_id,
            "thrilliant-agent-profile-name" => $agent_name,
            "thrilliant-welcome-company-title" => $company_name,
            "thrilliant-welcome-welcome-title" => $agent_name,//$agent_title,
            "thrilliant-welcome-welcome-text" => addslashes(str_replace("'", "",$agent_profile)),
            "thrilliant-welcome-view-more-text" => "read more!",
            "thrilliant-welcome-view-more-link" => "/about-us",
            "thrilliant-welcome-video" => $agent_video,
            "thrilliant-agent-profile-photo" => $agent_image_id,
            "thrilliant-agent-profile-phone-number" => '{"country":"1","phone":"'.$agent_phone.'","show":""}',
            "thrilliant-agent-profile-office-number" => '{"country":"1","phone":"'.$agent_phone.'","show":""}',
            "thrilliant-agent-profile-position" => $agent_position,
            "thrilliant-agent-profile-email" => $agent_email,
            "thrilliant-agent-profile-address" => $agent_address,
            "thrilliant-social-media-feed-instagram-feed" => 0,
            "thrilliant-social-media-feed-facebook-feed" => ($facebook? 1 : 0),
            "thrilliant-cta-hide-section" => 0,
            "thrilliant-cta-cta" =>'{"cta":[{"titleA":"Find My","titleB":"Dream Home","link":"/find-my-dream-home/"},{"titleA":"What Is My","titleB":"Property Value?","link":"/what-is-my-property-value/"},{"titleA":"Join Our","titleB":"Mailing List","link":"/mailing-list/"}]}',
            "thrilliant-idx-provider-hide-section" => 1,
            "thrilliant-idx-provider-property-title-big" => "Featured Property",
            "thrilliant-idx-provider-idxProvider" => "iHomefinder",
            "thrilliant-idx-provider-ihf-url" => "{{blogurl}}/homes-for-sale-featured/",
            "thrilliant-communities-hide-section" => 0,
            "thrilliant-communities-community-title-big" => "Featured Communities",
            "thrilliant-communities-community-title-small" => "Explore Our Neighborhood",
            "thrilliant-communities-more-community" => "View All",
            "thrilliant-communities-more-community-link" => "/our-communities",
            "thrilliant-our-partner-hide-section" => 1,
            "thrilliant-blog-hide-section" => ($blogs_show==1? 0 : 1),
            "thrilliant-blog-blog-title" => "Latest News",
            "thrilliant-blog-rss-feed" => $blogs_feed_prodvider,
            "thrilliant-blog-blog-count" => 3,
            "thrilliant-contact-us-contactus-title" => "Contact Me",
            "thrilliant-contact-form-contact-from-shortcode" => '[contact-form-7 id="'.$contact_form_id.'" title="Contact Form"]',
            "thrilliant-footer-brokerage-logo" => $logo_id,
            "thrilliant-social-media-facebook" => $facebook,
            "thrilliant-social-media-twitter" => $twitter,
            "thrilliant-social-media-instagram" => $instagram,
            "thrilliant-social-media-linkedin" => $linkedin,
            "thrilliant-social-media-pinterest" => $pinterest,
            "thrilliant-social-media-youtube" => $youtube,
            // "thrilliant-social-media-zillow" => $zillow,
            "thrilliant-social-media-yelp" => $yelp,
            "thrilliant-theme-colors-selected-color" => $template_color_name,
            "thrilliant-theme-colors-hide-section" => 1,
            "thrilliant-testimonials-testimonial-title-big" => 'Testimonials',
            "thrilliant-testimonial-hide-section" => $testimonials_show
            // "thrilliant-video-image" => $video_image_id,
            // "thrilliant-video-heading" => $video_title,
            // "thrilliant-video-description" => addslashes($video_description),
            // "thrilliant-video-video" => $video_link,
            // "thrilliant-our-partner-heading" => 'Our Partners',
            // "thrilliant-our-partner-subheading" => $partner_heading,
            // "thrilliant-our-partner-description" => addslashes(str_replace("'", "",$partner_description))
        );

      $theme_setting = json_encode($theme_settings);

      $ssh->exec("cd public_html && wp option update ".$theme_settings_name." '".$theme_setting."' --format=json");

      $ssh->exec("cd public_html && wp option update ihf_activation_token '791DAE21-E9F3-5D24-D2102CF103A175B1' ");
      $ssh->exec("cd public_html && wp rewrite flush && wp rewrite structure '/%postname%' && wp option update blogname ".$website_title);

      User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'data-import',
            ]);

      die('job done');
    }
}
