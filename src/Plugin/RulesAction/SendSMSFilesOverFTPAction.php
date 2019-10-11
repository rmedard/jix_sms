<?php


namespace Drupal\jix_sms\Plugin\RulesAction;


use Drupal;
use Drupal\rules\Core\RulesActionBase;
use FtpClient\FtpClient;
use FtpClient\FtpException;

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
        try {
            $ftpClient = new FtpClient();
            $ftpClient->connect('www', false, 990);
            $ftpClient->login('username', 'password');
        } catch (FtpException $e) {
            Drupal::logger('jix_sms')
                ->error('FTP Error Code: ' . $e->getCode() . ' | Message: ' . $e->getMessage());
        }
    }
}