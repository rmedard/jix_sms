<?php


namespace Drupal\jix_sms\Plugin\RulesAction;


use Drupal;
use Drupal\rules\Core\RulesActionBase;

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
        $connection = ssh2_connect('sftp.mtarget.fr', 31022);
        $loggedIn = ssh2_auth_password($connection, 'jobincameroun', 'GcsJXxKaDY');
        if ($loggedIn) {
//            $sftp = ssh2_sftp($connection);
            Drupal::logger('jix_sms')->info('Login Successful...');
            ssh2_disconnect($connection);
        } else {
            Drupal::logger('jix_sms')->error('Login Failed...');
        }
    }
}