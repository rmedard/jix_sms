<?php


namespace Drupal\jix_sms\Plugin\RulesAction;


use Drupal;
use Drupal\rules\Core\RulesActionBase;
use phpseclib\Net\SFTP;

/**
 * Class SendSMSFilesOverFTPAction
 * @package Drupal\jix_sms\Plugin\RulesAction
 *
 * @RulesAction(
 *     id = "rules_action_send_sms_files",
 *     label = @Translation("Send SMS Files over FTP Action"),
 *     category = @Translation("Content")
 * )
 */
class SendSMSFilesOverFTPAction extends RulesActionBase
{
    /**
     * doExecute
     */
    protected function doExecute()
    {
        $config = Drupal::config('jix_sms.general.settings');
        $host = $config->get('mtarget_ftp_host');
        $port = intval($config->get('mtarget_ftp_port'));
        $username = $config->get('mtarget_ftp_username');
        $password = $config->get('mtarget_ftp_password');
        Drupal::logger('jix_sms')->info('username: ' . $username . ' | Password: ' . $password);
        $sftp = new SFTP($host, $port);
        $loggedIn = $sftp->login($username, $password);
        if (false === $loggedIn) {
            Drupal::logger('jix_sms')->error('Login Failed. 
            Make sure the outgoing port is open on this server.');
        } else {
            Drupal::logger('jix_sms')->info('Login Successful...');
            Drupal::logger('jix_sms')->info(print_r($sftp->nlist()));
            if ($sftp->isConnected()) {
                $sftp->disconnect();
            }
        }
    }
}