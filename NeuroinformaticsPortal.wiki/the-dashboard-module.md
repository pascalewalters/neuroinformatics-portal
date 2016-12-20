# Brain-CODE Dashboard Module
The Spotfire module code that was provided by OBI through Confluence is for a Drupal 7 implementation of the portal (https://indocconsortium.atlassian.net/wiki/display/CAMH/Spotfire+Integration).
There has been a large overhaul in the way that modules and themes are coded in the transition to Drupal 8.
The challenge with creating a dashboard module for the CAMH Neuroinformatics Platform is converting all methods and variable names to values that can be used in Drupal 8.

The Drupal 7 module has a dependency on another module, called Mica Project.
This module creates a content type called Project with the following properties:
* Each Project content type represents a research program
* Each Project can have multiple studies
* Each user is associated with one or more Projects

Unfortunately, Mica Project has not been implemented for Drupal 8.
For this reason, a new way of associating users with research programs is required.

The Spotfire module from OBI works in the following way (found in braincode_user_dashboard.module):
* Using a hook_node_view(), if the node being viewed is the Dashboard node, call the addJavascript() function
* Retrieve a list of research programs associated with the user
* Generate an array of program node IDs (one for each research program) for the user
* Query the database to get another list of program node IDs (?)
* Generate JavaScript that places the dashboard on the page
  * Creates one tab per research program associated with the user
  * To load the Spotfire dashboard, a programGenVisUrl value is needed. This probably is a URL associated with the particular research program's dashboard

To modify the module for use with the Drupal 8 implementation of the Neuroinformatics Platform, a change in the way users are associated with research programs is required, first and foremost.
While it would be nice to have a list of research programs associated with each user and a list of studies associated with each research program, this may prove to be very difficult and time-consuming without the use of the Mica Project module.
My proposed solution is to implement various permission levels associated with each research program and ignore possible studies.
Once user accounts are created, they are associated with research programs by assigning roles.

To access the Spotfire dashboards for each research program, a URL is required.

Documentation available online for creating Drupal 8 modules is rather difficult to follow and assumes extensive knowledge of how Drupal 8 works.
For this reason, I have been developing the Neuroinformatics Platform module based off of an example module called js_example.

# CAMH Neuroinformatics Platform Dashboard Module
The dashboard module seems to be working fine.
We just need the Spotfire dashboards to be developed and the module will be complete. Work needs to be done in js/userDashboard.js
Currently, the module throws errors because it can't find the dashboards or the Api.js file that defines the Spotfire objects. Once the dashboard URLs are provided, they can be included in js/userDashboard.js where labelled.

Information about the Spotfire API can be found here: http://stn.spotfire.com/stn/Tutorials/WebPlayerOpenFile.aspx

Research programs have been implemented as roles.
This allows users to be added to multiple programs at the time of their account creation and is rather easy to use during the implementation of the dashboard module.
The names of the research programs and their associated dashboard URLs require an administrator to access [webtester.camhres.ca/admin/config/user-dashboard/default](http://webtester.camhres.ca/admin/config/user-dashboard/default).

Instructions on how to add users to research programs can be found on the [administration page](administration).

Ideally, the module would create the roles for each of the research programs automatically, as they are typed into the configuration form.
However, Drupal doesn't seem to like when roles are created by a module after it has been installed or enabled.
It gives the "white screen of death" ([https://www.drupal.org/node/158043](https://www.drupal.org/node/158043)) when I try to implement this.

# File Structure of Dashboard Module
**user_dashboard.info.yml**
* Contains information about the module, such as its name and a description

**user_dashboard.libraries.yml**
* Since this module makes use of a JavaScript file, it must be included in the module through a library, called user_dashboard.dashboard
* jQuery is included as a dependency in the library
* drupalSettings allows variables to be passed from the module's controller to the JavaScript file

**user_dashboard.routing.yml**
* This allows the dashboard to be viewed at the url [webtester.camhres.ca/dashboard](http://webtester.camhres.ca/dashboard)
* This file also declares the controller and the method that implements it (getUserDashboardImplementation)
* To view the dashboard, users must be authenticated and have permission to access content (this is true for all authenticated users)
  * This also hides the link for the dashboard from the main navigation menu when the user is not logged in
* This also allows the configuration for the research program names and URLs to be views at [webtester.camhres.ca/admin/config/user-dashboard/default](http://webtester.camhres.ca/admin/config/user-dashboard/default) (must be logged in as an administrator)

**user_dashboard.module**
* Implements a help page for the module which can be accessed at [webtester.camhres.ca/admin/help/user_dashboard](http://webtester.camhres.ca/admin/help/user_dashboard)

**user_dashboard.menu.links.yml**
* Allows for the configuration page to be accessed from [webtester.camhres.ca/admin/config](http://webtester.camhres.ca/admin/config)

**src/Form/DefaultForm.php**
* Implements the configuration form that allows the user to enter research program names and URLs

**src/Controller/UserDashboardController.php**
* The library user_dashboard.dashboard is also attached to the JavaScript file
`$build['#attached']['library'][] = 'user_dashboard/user_dashboard.dashboard';`
  * `user_dashboard.dashboard` is the name of the library from user_dashboard.libraries.yml
* In this file, the divs for the page are set in a build array
* The build array also allows variables from the controller to be accessed in the JavaScript files
  * This is done in the following way:
`$build['#attached']['drupalSettings']['user_dashboard']['user_programs'] = $user_programs;`
  * `user_dashboard` is the name of the module
  * `user_programs` is the name of the variable that can be accessed from the JavaScript file
* `user_programs` is the only variable that can be accessed in the JavaScript file from the controller.
  * It is an associative array with the following form:

```
$user_programs = array(
  array('full_name' => 'Research Program 1',
    'machine_name' => 'research_program_1',
    'url' => 'research_program_1_url'),
  );
```
  * It represents all research programs to which the user belongs

**js/userDashboard.js**
* This is the JavaScript file that determines the function of the page
* Drupal has a strange way of attaching JavaScript functions to the page
  * All functions must be included in the following way:

```
(function ($, Drupal, drupalSettings) {
  'use strict';
    Drupal.behaviours.userDashboard = {
      attach: function (context, settings) {
        // Make the page do things here
      }
    };
  })(jQuery, Drupal, drupalSettings);
```
  * This means that the function is attached to the Drupal behaviour userDashboard
* Variables from the controller are accessed with the following code:

```
drupalSettings.user_dashboard.user_programs
```
  * This accesses the user_projects variable as declared in UserDashboardController.php

# TODOs for Dashboard Module
* In js/userDashboard.js, add the correct URL for the Spotfire API when it becomes available

```
// TODO: Insert correct API URL when it becomes available
jQuery("head").append("<script type=\"text/javascript\" src=\"https://sfweb.braincode.ca/SpotfireWeb/GetJavaScriptApi.ashx?Version=1.0\"></script>");
```

* In js/userDashboard.js, make sure the document.domain value is correct

```
// TODO: Make sure the document is correct
// The document domain value must match the value on the Spotfire Web Player configuration
visFnCall += "document.domain = \"webtester.camhres.ca\";";
```

* In js/userDashboard.js, use the correct URL in app instantiation

```
// TODO: User correct URL in app instantiation
// Instantiate the Spotfire application and open the appropriate document
visFnCall += "var app = new spotfire.webPlayer.Application(\"https://sfweb.braincode.ca/SpotfireWeb/\", customization);";
```

* At http://webtester.camhres.ca/admin/config/user-dashboard/default, make sure the correct dashboard names and associated URLs are listed
* Create roles for each dashboard
* Delete roles and URLs for dashboards that do not exist
* Assign roles for research programs to users