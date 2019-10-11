<?php


namespace Drupal\jix_sms\Plugin\RulesAction;


use Drupal;
use Drupal\rules\Core\RulesActionBase;
use http\Exception\UnexpectedValueException;
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
        $sftpClient = new SFTP('sftp.mtarget.fr', 31022);
        try {
            if (!$sftpClient->login('jobincameroun', 'GcsJXxKaDY')) {
                Drupal::logger('jix_sms')->error(json_encode($sftpClient->getLog()));
            } else {
                Drupal::logger('jix_sms')->info('Login Successful...');
                $sftpClient->disconnect();
            }
        } catch (UnexpectedValueException $exception) {
            Drupal::logger('jix_sms')->error('Message: ' . $exception->getMessage());
        }
    }
}