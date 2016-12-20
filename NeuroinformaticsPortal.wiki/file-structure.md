# File Structure

### Theme
The custom theme currently used by the portal is called Inherit and can be found in themes/custom/inherit. It is based on the Zircon theme that was contributed to the Drupal website. The Zircon theme can be found in themes/contrib/zircon.

Within the Inherit theme, information about the theme can be found in inherit.info.yml and the list of dependencies can be found in inherit.libraries.yml. The templates directory contains page.html.twig, which describes the layout of the page using the block regions. CSS files are found in themes/custom/inherit/css.

### Modules
Older modules can be found in sites/all/modules. For some reason, the portal crashes when these modules are moved to the modules/ directory. They were put there before realizing that the Drupal 8 file structure is different that that of Drupal 7. New modules can be found in modules/.
**Do not touch the modules in sites/all/modules!** Deleting them or moving them can cause the portal to crash and it is difficult to bring it back up.
Be sure to uninstall a module before deleting it.

### Other Files
Inline images are found in sites/default/files/inline-images. They are added to content types using the text editor.

The core/ and all files in the top directory (except for README.md) come from the Drupal core. They currently are for Drupal 8.2.3.