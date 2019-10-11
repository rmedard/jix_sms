<?php


namespace Drupal\jix_sms\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class JixSMSConfigurationForm extends ConfigFormBase
{

    const SETTINGS = 'jix_sms.daily.sms';

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
        $form['number.daily.jobs'] = array(
            '#type' => 'number',
            '#title' => $this->t('Number of jobs'),
            '#default_value' => $config->get('jix_sms.dailysms')->get('nbr'),
            '#description' => $this->t('Number of job sms files to generate at a time.')
        );
        $form['mtarget.ftp.host'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Host'),
            '#default_value' => $config->get('jix_sms.dailysms')->get('nbr'),
            '#description' => $this->t('Host server name')
        );
        $form['mtarget.ftp.port'] = array(
            '#type' => 'number',
            '#title' => $this->t('Port'),
            '#default_value' => $config->get('jix_sms.dailysms')->get('nbr'),
            '#description' => $this->t('Host server port')
        );
        $form['mtarget.ftp.username'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Username'),
            '#default_value' => $config->get('jix_sms.dailysms')->get('nbr')
        );
        $form['mtarget.ftp.password'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Password'),
            '#default_value' => $config->get('jix_sms.dailysms')->get('nbr')
        );
        return parent::buildForm($form, $form_state);
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->isValueEmpty('number_of_jobs')) {
            $form_state->setErrorByName('number_of_jobs', t('Number of jobs cannot be empty nor 0'));
        } else {
            if ($form_state->getValue('number_of_jobs') > 5) {
                $form_state->setErrorByName('number_of_jobs', t('Number of jobs must be between 1 and 5'));
            }
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        parent::submitForm($form, $form_state);
        $this->config('jix_sms.dailysms')->set('nbr', $form_state->getValue('number_of_jobs'));
    }
}