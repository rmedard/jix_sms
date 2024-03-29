<?php


namespace Drupal\jix_sms\Form;


use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use phpseclib\Net\SFTP;

class JixSMSConfigurationForm extends ConfigFormBase
{

    const SETTINGS = 'jix_sms.general.settings';

    /**
     * Gets the configuration names that will be editable.
     *
     * @return array
     *   An array of configuration object names that are editable if called in
     *   conjunction with the trait's config() method.
     */
    protected function getEditableConfigNames()
    {
        return [static::SETTINGS];
    }

    /**
     * Returns a unique string identifying the form.
     *
     * The returned ID should be a unique string that can be a valid PHP function
     * name, since it's used in hook implementation names such as
     * hook_form_FORM_ID_alter().
     *
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId()
    {
        return 'jix_sms_settings_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config(static::SETTINGS);
        $form['number_daily_jobs'] = array(
            '#type' => 'number',
            '#title' => $this->t('Number of jobs'),
            '#default_value' => $config->get('number_daily_jobs'),
            '#required' => true,
            '#description' => $this->t('Number of job sms files to generate at a time.')
        );
        $form['ftp_settings'] = array(
            '#title' => $this->t('FTP Settings'),
            '#type' => 'fieldset',
            '#collapsible' => false,
            '#collapsed' => false,
        );
        $form['ftp_settings']['mtarget_ftp_host'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Host'),
            '#default_value' => $config->get('mtarget_ftp_host'),
            '#required' => true,
            '#description' => $this->t('Host server name')
        );
        $form['ftp_settings']['mtarget_ftp_port'] = array(
            '#type' => 'number',
            '#title' => $this->t('Port'),
            '#default_value' => $config->get('mtarget_ftp_port'),
            '#required' => true,
            '#description' => $this->t('Host server port')
        );
        $form['ftp_settings']['mtarget_ftp_username'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Username'),
            '#default_value' => $config->get('mtarget_ftp_username'),
            '#description' => $this->t('SFTP server username. Leave blank if not required')
        );
        $form['ftp_settings']['mtarget_ftp_password'] = array(
            '#type' => 'password',
            '#title' => $this->t('Password'),
            '#default_value' => $config->get('mtarget_ftp_password'),
            '#description' => $this->t('The currently set password is hidden for security reasons.')
        );
        return parent::buildForm($form, $form_state);
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->isValueEmpty('number_daily_jobs')) {
            $form_state->setErrorByName('number_daily_jobs', t('Number of jobs cannot be empty nor 0'));
        } else {
            if ($form_state->getValue('number_daily_jobs') > 5) {
                $form_state->setErrorByName('number_daily_jobs', t('Number of jobs must be between 1 and 5'));
            }
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $host = $form_state->getValue('mtarget_ftp_host');
        $port = intval($form_state->getValue('mtarget_ftp_port'));
        $username = $form_state->getValue('mtarget_ftp_username');
        $password = $form_state->getValue('mtarget_ftp_password');
        if (empty($password)) {
            $password = Drupal::config('jix_sms.general.settings')->get('mtarget_ftp_password');
        }

        $this->configFactory->getEditable(static::SETTINGS)
            ->set('number_daily_jobs', $form_state->getValue('number_daily_jobs'))
            ->set('mtarget_ftp_host', $host)
            ->set('mtarget_ftp_port', $port)
            ->set('mtarget_ftp_username', $username)
            ->set('mtarget_ftp_password', $password)
            ->save();
        parent::submitForm($form, $form_state);

        $sftp = new SFTP($host, $port);
        $loggedIn = $sftp->login($username, $password);
        if (false === $loggedIn) {
            Drupal::messenger()->addWarning('Connection to SFTP server could not be verified...');
        } else {
            Drupal::messenger()->addStatus('Connection to SFTP server is successful...');
            if ($sftp->isConnected()) {
                $sftp->disconnect();
            }
        }
    }
}