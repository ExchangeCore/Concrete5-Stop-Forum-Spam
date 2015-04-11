<?php
namespace Concrete\Package\EcSfs\Src\Antispam;

use Concrete\Core\Utility\IPAddress;
use Config;
use Core;

class EcSfsController
{
    public function check($args)
    {
        $query = array('f' => 'json', 'ip' => $args['ip_address']);

        if (isset($args['email'])) {
            $query['email'] = $args['email'];
        }

        $response = Core::make('helper/file')->getContents('http://api.stopforumspam.org/api?' . http_build_url($query));
        if ($response) {
            $response = json_decode($response);
            
            if (isset($response['ip']) && $response['ip']['appears'] && $response['ip']['confidence'] > 90) {
                return false;
            }
            
            if (isset($response['email']) && $response['email']['appears'] && $response['email']['confidence'] > 90) {
                return false;
            }
        }

        return true;
    }

    public function report($args)
    {
        $apiKey = \Package::getByHandle('ec_sfs')->getConfig()->get('api.key');

        if ($apiKey) {
            $ch = curl_init('http://www.stopforumspam.com/add.php');

            if (Config::get('concrete.proxy.host') != null) {
                @curl_setopt($ch, CURLOPT_PROXY, Config::get('concrete.proxy.host'));
                @curl_setopt($ch, CURLOPT_PROXYPORT, Config::get('concrete.proxy.port'));

                // Check if there is a username/password to access the proxy
                if (Config::get('concrete.proxy.user') != null) {
                    @curl_setopt(
                        $ch,
                        CURLOPT_PROXYUSERPWD,
                        Config::get('concrete.proxy.user') . ':' . Config::get('concrete.proxy.password'));
                }
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('ip_addr' => $args['ip_address']->getIp(IPAddress::FORMAT_IP_STRING), 'username' => $args['author'], 'email' => $args['author_email'], 'api_key' => $apiKey));
            curl_exec($ch);
            curl_close($ch);
        }
    }

    public function saveOptions($args)
    {
        $config = \Package::getByHandle('ec_sfs')->getConfig();
        $apiKey = trim($args['apiKey']);
        if (empty($apiKey)) {
            $config->save('api.key', null);
        } else {
            $config->save('api.key', $apiKey);
        }
    }
}