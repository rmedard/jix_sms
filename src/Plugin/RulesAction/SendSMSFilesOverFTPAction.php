<?php


namespace Drupal\jix_sms\Plugin\RulesAction;


use Drupal;
use Drupal\rules\Core\RulesActionBase;
use Net_SFTP;

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
//        Drupal::logger('jix_sms')->info('Action executed...');
        $sftp = new Net_SFTP('sftp.mtarget.fr', 31022);
        $loggedIn = $sftp->login('jobincameroun', 'GcsJXxKaDY');
        if (false === $loggedIn) {
            Drupal::logger('jix_sms')->error('Login Failed...');
        } else {
            Drupal::logger('jix_sms')->info('Login Successful...');
        }

//        $connection = ssh2_connect('sftp.mtarget.fr', 31022);
//        if (false === $connection) {
//            Drupal::logger('jix_sms')->error('SSH Connection Failed...');
//        } else {
//            $loggedIn = ssh2_auth_password($connection, 'jobincameroun', 'GcsJXxKaDY');
//            if (false === $loggedIn) {
//                Drupal::logger('jix_sms')->error('SSH Login Failed...');
//            } else {
//                ssh2_disconnect($connection);
//                Drupal::logger('jix_sms')->info('Login Successful...');
//            }
//        }
    }
}