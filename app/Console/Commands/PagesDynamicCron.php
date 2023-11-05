<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use App\Server;
use App\Helpers\CronsHelper;
use phpseclib3\Net\SSH2;

class PagesDynamicCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagesdynamic:cron';

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
      $order  =   User::where('status','pages')->first();

      if(!$order) 
         return false;

      $ssh = new SSH2($Server->ip, $order->ssh_port);
      if(!$ssh->login($order->ssh_username, $order->ssh_password)) 
         exit('Login Failed');

        $info_section = Customization::where(
                     ['order_id' => $order->order_number, 'meta_name' => 'info'])
                  ->first();
      
        $extract_info = json_decode($info_section['meta_value']);


      if($extract_info->theme_name == 'Premiere')
      {
        $contact_form_html = '<div class="row"><div class="col-6">[text* fname placeholder"First Name"]</div><div class="col-6">[text* lname placeholder"Last Name"]</div><div class="col-6">[tel* phoneno placeholder"Phone"]</div><div class="col-6">[email* your-email placeholder"Email"]</div><div class="col-12">[textarea your-message placeholder"Message"]</div><div class="col-12">[submit "Submit"]</div></div>';
      } else {
        $contact_form_html ='<div class="form-group"><div class="form-row"><div class="col-6">[text* name placeholder"Name"]</div><div class="col-6">[email* your-email placeholder"Email"]</div></div></div><div class="form-group"><div class="form-row"><div class="col-12">[textarea your-message placeholder"Message"]</div></div></div><div class="form-group m-0 button">[submit "Submit"]</div>';
      }


      $ssh->exec("cd public_html && wp post meta update $(wp post list --post_type=wpcf7_contact_form --allow-root --field=ID) _form '".$contact_form_html."' ");

      $mailing_list_id=$ssh->exec("cd public_html && wp post create --post_title='Mailing List' --post_status='publish' --post_type='wpcf7_contact_form' --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=wpcf7_contact_form");
      $mailing_list_id=str_replace('\n', '', trim($mailing_list_id));
      $mailing_list_html='[contact-form-7 id="'.$mailing_list_id.'" title="Mailing List"]';
      $mailing_list_form_html='<div class="row"><div class="col-12"><h3>MAILING LIST</h3></div><div class="col-6">[text* first-name placeholder"First Name *"]</div><div class="col-6">[text* last-name placeholder"Last Name *"]</div><div class="col-6">[email* your-email placeholder"Email Address *"]</div><div class="col-6">[tel* phone-no placeholder"Phone Number *"]</div><div class="col-12">[textarea additional-comments placeholder"Additional Comments"]</div><div class="col-12 button">[submit "Submit"]</div></div>';
      $ssh->exec("cd public_html && wp post meta update ".$mailing_list_id." _form '".$mailing_list_form_html."' ");
      $ssh->exec("cd public_html && wp post create --post_title='Mailing List' --post_content='".$mailing_list_html."' --post_status='publish' --post_type='page'");

      $home_value_id=$ssh->exec("cd public_html && wp post create --post_title='What Is My Property Value' --post_status='publish' --post_type='wpcf7_contact_form' --porcelain | xargs -I {} wp post list --post__in={} --field=ID --post_type=wpcf7_contact_form");
      $home_value_id=str_replace('\n', '', trim($home_value_id));
      $home_value_html='[contact-form-7 id="'.$home_value_id.'" title="What Is My Property Value"]';
      $home_value_form_html='<p><strong>Selling your home?</strong><br>We are here to help you price it right – get a comparative market analysis today.</p><div class="contactinfo-form"><div class="row main"><div class="col-6 main"><div class="row inner"><div class="col-12"><h3>CONTACT INFORMATION</h3></div><div class="col-6">[text* your-name placeholder"Name *"]</div><div class="col-6">[email* your-email placeholder"Email *"]</div><div class="col-12">[tel* phone-no placeholder"Phone *"]</div><div class="col-12">[text address placeholder"Address"]</div><div class="col-4">[text city placeholder"City"]</div><div class="col-4">[text state placeholder"State"]</div><div class="col-4">[number zipcode placeholder"Zip"]</div><div class="col-12">[text approximate-date placeholder"Approximate Date of Move"]</div><div class="col-12">[select preferred-method "Preferred Method of Contact" "Phone" "Email" "Phone or Email"]</div></div></div><div class="col-6 main"><div class="row inner"><div class="col-12"><h3>HOME SPECIFICATIONS</h3></div><div class="col-12">[select property-type "Property Type" "Single Family Home" "Condominium / Townhouse" "Income Property"]</div><div class="col-6">[select bedrooms "Bedrooms" "1" "2" "3" "4" "5+"]</div><div class="col-6">[select bathrooms "Bathrooms" "1" "2" "3" "4" "5+"]</div><div class="col-12">[text square-footage placeholder"Square Footage"]</div><div class="col-12">[textarea additional-comments placeholder"Additional Comments"]</div></div></div><div class="col-12 button">[submit "Submit"]</div></div></div>';
      $ssh->exec("cd public_html && wp post meta update ".$home_value_id." _form '".$home_value_form_html."' ");
      $ssh->exec("cd public_html && wp post create --post_title='What Is My Property Value' --post_content='".$home_value_html."' --post_status='publish' --post_type='page'");

      $ssh->exec("cd public_html && wp post create --post_title='Find My Dream Home' --post_content='[optima_express_advanced_search]' --post_status='publish' --post_type='page'");


      // $pages = WebsitePages::find()->andWhere(['website_id' => $Website->id, 'status' => 1, 'trashed' => 0])->all();
      // if($pages){
      //   foreach ($pages as $page) {
      //     $ssh->exec("cd public_html && wp post create --post_title='".$page->title."' --post_content='".$page->description."' --post_status='publish' --post_type='page'");
      //   }
      // }
      
         User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'dynamic-pages',
            ]);
         
      return  "Dynamic Pages added!";
    }
}
