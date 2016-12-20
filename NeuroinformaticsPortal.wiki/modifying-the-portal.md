# Updating Drupal Core
Check for updates at http://webtester.camhres.ca/admin/reports/updates/update

Drupal must be manually updated to newer versions.
Documentation on doing this can be found [here](https://www.drupal.org/docs/8/update/update-procedure-in-drupal-8).
[This step-by-step video](https://youtu.be/JUIdn7-A62Q) is also helpful.

Be sure to back up the site and put it into maintenance mode when this is happening.

# Updating Contributed Modules
Check for updates at http://webtester.camhres.ca/admin/reports/updates/update

Drupal modules must also be updated manually.
Instructions to do this can be found [here](https://www.drupal.org/docs/7/updating-your-drupal-site/updating-modules).

Basically, the older versions of the modules must be deleted and the new ones installed as though they were new modules. This means they must be downloaded from Drupal.org again.

Be sure to back up the site and put it into maintenance mode when this is happening.

# Content Types
The portal uses several custom content types. They are as follows:
* About Type (for content that appears on the About page)
* Analytics Type (for analytics tools that will appear on the Analytics page)
* Database (for electronic data capture tools that appear on the Data Capture Tools view)
* Database Help (for information on each data capture tool)
* FAQ Type (for content that appears on the FAQ page)
* Getting Started Type (for content that appears on the Getting Started page)
* Jssor Slider (for images that are to be included in the slideshow)
* Research Program Type (for content that appears on the Research Program view and the view that gives more information about that particular research program)
* Spotfire (for the image that is in place temporarily for the Spotfire plugin)
* Welcome Page Type (for content that appears on the landing page)

Some content types have fields for Text (Formatted, Long). If this is the case, the field can be set to have the Text format as Full HTML. This means that HTML tags and Bootstrap classes can be used in the source code.

# Editing Existing Information Pages
These instructions are for editing information pages, i.e. About page, FAQ, Getting Started, Welcome Page and data capture tool information pages. However, similar steps are followed to edit all other content types.

* Click on Content in the Administrator toolbar
* Find the item you wish to edit. Their names are as follows:

| Page Name | Content Name | Content Type |
| :-------: | :----------: | :----------: |
| Landing Page | Welcome Page 2 | Welcome Page Type |
| About | About Neuroinformatics Platform | About Type |
| FAQ | FAQ | FAQ Type |
| Getting Started | Getting Started | Getting Started Type | Getting Started Type |
| Data Capture Tool Information Page | [Name of Data Capture Tool] | Database Help |

* Click Edit to the right of the content
* Edit the content as desired. Clicking on Source at the top of the text editing box allows for much more flexibility when writing content as HTML.
  * Drupal supports Bootstrap classes (e.g. col-sm-6, btn btn-default)
* Click Save and keep published at the bottom of the page
 
# Data Capture and Analytics Tools Images
The images that are used for the Database and Analytics content type are 303 x 100 px.

# Adding Data Capture Tools
* Add content of the type Database ([webtester.camhres.ca/node/add/database](http://webtester.camhres.ca/node/add/database))
* For the Database Link field, include the image of the data capture tool (the logo, for example) in an `<a>` tag
  * Add the image by clicking on the Image button in the text editor, then click the Source button
  * Surround the image in an `<a>` tag. Leave `href="#"` for now
  * Make sure the Text format is Full HTML and you're typing after clicking the Source button
* For the Database button, add buttons that link to the training (test) and data entry (production) sites
  * Make sure the Text format is Full HTML and you're typing after clicking the Source button
  * For example,

```
<table align="center" border="0" cellpadding="1" cellspacing="1" style="width: 350px;">
    <tbody>
        <tr>
            <td class="text-align-center">
                <a class="btn btn-default" href="https://redcap.camh.ca/redcap/" style="width:150px;" type="button">Enter Data</a>
            </td>
            <td class="text-align-center">
                <a class="btn btn-default" href="https://redcap.camh.ca/redcap/" style="width:150px;" type="button">Training Site</a>
            </td>
        </tr>
    </tbody>
</table>
```
  * This puts two buttons in a table that is centred nicely
* Click Save and publish
* Now, add content of type Database Help ([webtester.camhres.ca/node/add/database_help](http://webtester.camhres.ca/node/add/database_help))
  * Add documentation for users to understand the data capture tools
* On the right of the screen, under URL Path Settings, add a meaningful URL alias
  * The current data capture tools have the pattern /data-capture-tools/name-of-tool
  * Note this down. It will be needed later
* Click Save and publish
* On the Content page ([webtester.camhres.ca/admin/content](http://webtester.camhres.ca/admin/content)), edit the Database content type for the data capture tool that was just created
* While viewing the Source for the Database link, add the URL you just made to the `<a>` tag as the `href` value
* Click Save and keep Published
* Check the Data Capture Tools page to make sure every thing is working
  * Formatting problems can probably be fixed by editing the view ([webtester.camhres.ca/admin/structure/views/view/database_page](http://webtester.camhres.ca/admin/structure/views/view/database_page)) or editing the themes CSS files (/themes/custom/inherit/css)

# Adding Slideshow Pictures
Add content of the type **Jssor slider**. Images should be around 1300 x 340px.
Captions can be added to the picture in your favourite image editor.
The properties of the slideshow (eg. transition time, transition speed) are modified by going to Structure > Views > Slides.
Under the Format section, click Settings to the right of Jssor Slider.

It would be nice to have text overlay on the images of the captions, however the module doesn't work when this is configured.
The Jssor slider module has two formats: Jssor and Content.
The Jssor format allows for text overlay, but it does not automatically cycle through images. The Content format does work, but it does not allow for captions.

I tried to fix this by playing around with the code of the module, but this did not fix the problem. It would be good to keep an eye on Drupal.org (https://www.drupal.org/project/project_module) until a module for Drupal 8 that allows text overlay on images is available.

# Autologout Module
The Neuroinformatics Platform portal has a module included that forces a user's logout after 30 minutes of inactivity.
This can be modified by accessing Configuration > People > Autologout Settings or [webtester.camhres.ca/admin/config/people/autologout](http://webtester.camhres.ca/admin/config/people/autologout) when logged in as an administrator.