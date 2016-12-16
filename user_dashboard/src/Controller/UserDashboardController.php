<?php

namespace Drupal\user_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for user_dashboard pages.
 *
 * @ingroup user_dashboard
 */
class UserDashboardController extends ControllerBase {
 /**
   * In this controller, on the Drupal side, we do three main things:
   * - Create a container DIV, with an ID all the JavaScript script can recognize.
   * - Attach the JavaScript script which generate the Spotfire dashboard.
   * - Add the necessary arrays to drupalSettings, which is where Drupal
   *   passes data out to JavaScript.
   *
   * The JavaScript script (js/userDashboard.js) uses jQuery to find our
   * DIVs, and then add some content to it.
   *
   * @return array
   *   A renderable array.
   */
  public function getUserDashboardImplementation() {
    // Start building the content.
    $build = array();
    // Main container DIV. We give it a unique ID so that the JavaScript can
    // find it using jQuery.
    $build['content'] = array(
      '#markup' => '<div id="dbHeadTitle"></div>
                    <div id="studyTabs"></div>
                    <div id="sfDiv"></div>',
    );
    // Attach library containing js files.
    $build['#attached']['library'][] = 'user_dashboard/user_dashboard.dashboard';
    // Attach the arrays to our JavaScript settings. This allows the
    // JavaScript scripts we just attached to discover these values, by
    // accessing drupalSettings.user_dashboard.research_programs. The script
    // only uses this information for display to the user.

    // Get a list of the current user's role
    $user_roles = \Drupal::currentUser()->getRoles();

    // Get the string of research program names from the config page
    $program_names = \Drupal::service('config.factory')->getEditable('user_dashboard.default')->get('names');

    // Create an array of research program names with empty elements removed
    $programs_full_names = array_filter(explode("\n", $program_names));
    array_walk($programs_full_names, create_function('&$val', '$val = trim($val);'));

    // Create an array of machine names for each of the research programs
    $programs_machine_names = array();
    foreach ($programs_full_names as $program_full_name) {
      $programs_machine_names[] = strtolower(str_replace(' ', '_', $program_full_name));
    }

    // Get the string of research program URLs from the config page
    $urls = \Drupal::service('config.factory')->getEditable('user_dashboard.default')->get('urls');

    // Create an array of research program urls with empty elements removed
    $urls = array_filter(explode("\n", $urls));
    array_walk($urls, create_function('&$val', '$val = trim($val);'));

    // Create an associative array of research programs
    $programs = array();
    for ($i = 0, $size = count($programs_full_names); $i < $size; ++$i) {
	$programs[] = array(
		'full_name' => $programs_full_names[$i],
		'machine_name' => $programs_machine_names[$i],
		'url' => $urls[$i],
		);
    }

    $user_programs = array();

    // $user_prog is the same associative array, but only for the user's programs
    foreach ($programs as $program) {
	// $p is an array of properties
	if (in_array($program['machine_name'], $user_roles)) {
		$user_programs[] = $program;
	}
    }
    $build['#attached']['drupalSettings']['user_dashboard']['user_programs'] = $user_programs;

    return $build;
  }

}
