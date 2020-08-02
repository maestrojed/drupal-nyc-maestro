<?php

namespace Drupal\maestro_noninteractive_task_plugin_example;

use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\maestro\Engine\MaestroEngine;

/**
 * Prevents example task module from being uninstalled when the task is bound in a template.
 */
class MaestroNonInteractiveExampleTaskUninstallValidator implements ModuleUninstallValidatorInterface {

  use StringTranslationTrait;

  /**
   * Constructs a new MaestroExampleTaskUninstallValidator.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(TranslationInterface $string_translation) {
    // We only use string translation in this validator, the rest is up to the Maestro Engine.
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    $reasons = [];
    if ($module == 'maestro_noninteractive_task_plugin_example') {
      // Cycle through all of the Maestro templates and determine if any of the tasks are of type MaestroNonIntExample.
      $templates = MaestroEngine::getTemplates();
      foreach ($templates as $template) {
        foreach ($template->tasks as $task) {
          if ($task['tasktype'] == 'MaestroNonIntExample') {
            $reasons[] = $this->t('To uninstall the Non Interactive Plugin Task Example module, remove the Non Interactive Example task from the <em>:template</em> template.', [':template' => $template->label]);
          }
        }
      }
    }
    return $reasons;
  }

}
