/**
 * @file
 * Contains the definition of the behaviour userDashboard.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Attaches the userDashboard behavior to div.
   */
  Drupal.behaviors.userDashboard = {
    attach: function (context, settings) {
      // Print a list of the user's research programs
      console.log(drupalSettings.user_dashboard.user_programs);

      // TODO: 502 proxy error when calling this script
      jQuery("head").append("<script type=\"text/javascript\" src=\"https://sfweb.braincode.ca/SpotfireWeb/GetJavaScriptApi.ashx?Version=1.0\"></script>");
      
      // Resize the div
      document.getElementById("sfDiv").style.width = 1000 + "px";
      document.getElementById("sfDiv").style.height = 1000 + "px";
      
      // Set tab titles
      setTitle(); 

      // Trigger click event for the first tab
      jQuery("#studyTabs").find("li:first").trigger("click");

      function setTitle () {
        if (drupalSettings.user_dashboard.user_programs.length === 0) {
          document.getElementById("dbHeadTitle").innerHTML = "<h3 class=\"dashboard-header\">No programs or studies found.</h3>";
        } else {
          document.getElementById("dbHeadTitle").innerHTML = "<h1 class=\"dashboard-header\">Program Data Summary</h1>";
          
          var jsFn = "<ul class=\"nav nav-tabs\">";  
          var count = 0;

          drupalSettings.user_dashboard.user_programs.forEach(function(element) {
            count++;
            
            var visFnCall = openVisualization(element['machine_name'], element['url']);            
            if (count === 1) {
              jsFn += "<li id=\"" + element['machine_name'] + "\" class=\"active\" role=\"presentation\" onclick=\'" + visFnCall + "\'><a href=\"#\">";
            } else {
              jsFn += "<li id=\"" + element['machine_name'] + "\" class=\"\" role=\"presentation\" onclick=\'" + visFnCall + "\'><a href=\"#\">";
            }
            jsFn += element['full_name'] + "</a></li>";
          });
          jsFn += "</ul>";
          document.getElementById("studyTabs").innerHTML = jsFn;
        } 
      }

      function openVisualization(programCode, programGenVisUrl) {
        var visFnCall = "console.log(\"" + programCode + "\");";
        
        // Set only the selected tab as active
        visFnCall += "jQuery(\"#studyTabs .active\").removeClass(\"active\");";
        visFnCall += "jQuery(\"#" + programCode + "\").addClass(\"active\");";

        // Ensure we have valid function parameters
        if (!programCode || !programGenVisUrl) {
          return;
        }

        // The document domain value must match the value on the Spotfire Web Player configuration
        visFnCall += "document.domain = \"webtester.camhres.ca\";";

        // Customize the layout of the web player app
        visFnCall += "var customization = new spotfire.webPlayer.Customization();";
        visFnCall += "customization.showTopHeader = true;";
        visFnCall += "customization.showToolBar = true;";
        visFnCall += "customization.showExportFile = false;";
        visFnCall += "customization.showExportVisualization = true;";
        visFnCall += "customization.showCustomizableHeader = false;";
        visFnCall += "customization.showPageNavigation = true;";
        visFnCall += "customization.showStatusBar = false;";
        visFnCall += "customization.showDodPanel = false;";
        visFnCall += "customization.showFilterPanel = false;";

        // Instantiate the Spotfire application and open the appropriate document
        visFnCall += "var app = new spotfire.webPlayer.Application(\"https://sfweb.braincode.ca/SpotfireWeb/\", customization);";   
        visFnCall += "var configuration = \"\";";
        visFnCall += "app.open(\"" + programGenVisUrl + "\", \"sfDiv\", configuration);";

        return visFnCall;
      }  
    }
  };
})(jQuery, Drupal, drupalSettings);
