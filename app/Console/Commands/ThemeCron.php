<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Customization;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Theme;
use App\Server;
use phpseclib3\Net\SSH2;

class ThemeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:cron';

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
            // $dir_name = '/wamp/www/'.$user->order_number;
            // $setup_install = 'wp core install --url=http://localhost/'.$user->order_number;
            // $process = Process::fromShellCommandline( $setup_install.' --title="First theme of dynasty" --admin_user=admin --admin_password=admin --admin_email=developer.harisdstechnologies@gmail.com', 
            //     $dir_name);
            // $process->run();
            
        $Server =   Server::first();
          $order  =   User::where('status','ssl')->first();

          if (!$order) 
             return false;

          $ssh = new SSH2($Server->ip, $order->ssh_port);

          if (!$ssh->login($order->ssh_username, $order->ssh_password)) 
              exit('Login Failed');
          

           $website    = Customization::where(
                           ['order_id' => $order->order_number, 'meta_name' => 'info'])
                        ->first();

            $extract_info = json_decode($website['meta_value']);

            $Theme = Theme::where('key',$extract_info->cart_theme)->first();

            $arr_plugins = explode(" ",$Theme->plugins);

            $plugins_get = [];
            
            foreach ($arr_plugins as $arr_plugin) 
                $plugins_get[] = "https://stagefalcon.com/thrilliant_v2/public/plugins/".$arr_plugin.'.zip';

              $plugins = implode(" ",$plugins_get);

            // $arr = array(' https://stagefalcon.com/thrilliant_v2/public/plugins/contact-form-7.zip',
            //              'https://stagefalcon.com/thrilliant_v2/public/plugins/wp-file-manager.zip',
            //              'https://stagefalcon.com/thrilliant_v2/public/plugins/accessibe.zip',
            //              'https://stagefalcon.com/thrilliant_v2/public/plugins/all-in-one-wp-migration.zip',
            //              'https://stagefalcon.com/thrilliant_v2/public/plugins/breeze.zip',
            //              'https://stagefalcon.com/thrilliant_v2/public/plugins/malcare-security.zip',
            //              'https://stagefalcon.com/thrilliant_v2/public/plugins/contact-form-7.zip',
            //              'https://stagefalcon.com/thrilliant_v2/public/plugins/optima-express.zip',
            //              'https://stagefalcon.com/thrilliant_v2/public/plugins/wp-file-manager.zip'
            //           );

            // $old =  implode(" ",$arr);


            $ssh->exec('cd public_html && wp theme install  '.$Theme->link.' --activate');
            $ssh->exec('cd public_html && wp plugin install '.$plugins.' --activate');
            $ssh->exec('cd public_html && wp option update blogname "'.$extract_info->startup->project_name.'"');

            User::where(['order_number' => $order->order_number])->update([
                  'status'  => 'theme',
            ]);

    }
}
