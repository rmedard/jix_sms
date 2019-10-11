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
        $ftpClient = new SFTP('sftp://sftp.mtarget.fr', 31022);
        if (!$ftpClient->login('jobincameroun', 'GcsJXxKaDY')) {
            Drupal::logger('jix_sms')->error('Login Failed...');
            $ftpClient->disconnect();
        } else {
            Drupal::logger('jix_sms')->info('Login Successful...');
        }
    }
}