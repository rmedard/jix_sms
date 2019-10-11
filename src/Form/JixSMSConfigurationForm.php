<?php


namespace Drupal\jix_sms\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

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
            '#description' => $this->t('Number of job sms files to generate at a time.')
        );
        $form['default_center'] = array(
            '#title' => $this->t('FTP Settings'),
            '#description' => $this->t('FTP Settings.'),
            '#type' => 'fieldset',
            '#collapsible' => false,
            '#collapsed' => false,
        );
        $form['default_center']['mtarget_ftp_host'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Host'),
            '#default_value' => $config->get('mtarget_ftp_host'),
            '#description' => $this->t('Host server name')
        );
        $form['mtarget_ftp_port'] = array(
            '#type' => 'number',
            '#title' => $this->t('Port'),
            '#default_value' => $config->get('mtarget_ftp_port'),
            '#description' => $this->t('Host server port')
        );
        $form['mtarget_ftp_username'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Username'),
            '#default_value' => $config->get('mtarget_ftp_username')
        );
        $form['mtarget_ftp_password'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Password'),
            '#default_value' => $config->get('mtarget_ftp_password')
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
        $this->configFactory->getEditable(static::SETTINGS)
            ->set('number_daily_jobs', $form_state->getValue('number_daily_jobs'))
            ->set('mtarget_ftp_host', $form_state->getValue('mtarget_ftp_host'))
            ->set('mtarget_ftp_port', $form_state->getValue('mtarget_ftp_port'))
            ->set('mtarget_ftp_username', $form_state->getValue('mtarget_ftp_username'))
            ->set('mtarget_ftp_password', $form_state->getValue('mtarget_ftp_password'))
            ->save();
        parent::submitForm($form, $form_state);
    }
}