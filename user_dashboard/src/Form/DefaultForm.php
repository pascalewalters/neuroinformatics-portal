<?php


namespace Drupal\user_dashboard\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultForm.
 *
 * @package Drupal\user_dashboard\Form
 */
class DefaultForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'user_dashboard.default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('user_dashboard.default');
    $form['names'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Research Program Names'),
      '#description' => $this->t('Please enter the names of the research programs, each separated by a new line.'),
      '#default_value' => $config->get('names'),
    );
    $form['urls'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Research Program URLs'),
      '#description' => $this->t('Please enter the URLs for each research program database in the same order as above, each separated by a new line.'),
      '#default_value' => $config->get('urls'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
   public function validateForm(array &$form, FormStateInterface $form_state) {
     if (count(array_filter(explode("\n", $form_state->getValue('names')))) != count(array_filter(explode("\n", $form_state->getValue('urls'))))) {
       $form_state->setErrorByName(
         'names',
         $this->t("Make sure that there are the same number of URLs as research program names")
       );
     }
   }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('user_dashboard.default')
      ->set('names', $form_state->getValue('names'))
      ->save();

    $this->config('user_dashboard.default')
      ->set('urls', $form_state->getValue('urls'))
      ->save();
  }

}
