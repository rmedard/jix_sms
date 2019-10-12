<?php


namespace Drupal\jix_sms\Plugin\RulesAction;


use Drupal;
use Drupal\rules\Core\RulesActionBase;
use phpseclib\Net\SFTP;
use phpseclib\Net\SSH2;

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
        define('NET_SSH2_LOGGING', 2);
//        $sftp = new SFTP('sftp.mtarget.fr', 31022);
        $ssh = new SSH2('sftp.mtarget.fr', 31022);
//        $loggedIn = $sftp->login('jobincameroun', 'GcsJXxKaDY');
        $loggedIn = $ssh->login('jobincameroun', 'GcsJXxKaDY');
        if (false === $loggedIn) {
            Drupal::logger('jix_sms')->error('Login Failed...');
//            Drupal::logger('jix_sms')->error(json_encode($sftp->getSFTPErrors()));
        } else {
            Drupal::logger('jix_sms')->info('Login Successful...');
        }
    }
}