# Cakephp-Tinymce-Elfinder
Elfinder file manager integration with tinymce 4.x for **cakephp 3.x** - allows to upload/access images/files as into local file system, as well as Amazon S3, remote FTP server etc. 

For cakephp 2.x - https://github.com/hashmode/Tinymce-Image-Upload-Cakephp

## REQUIREMENTS
1. Cakephp 3.x
2. Elfinder 2.x (currently 2.1) https://github.com/Studio-42/elFinder
3. Tinymce 4.x (might work with 3.x as well)


## Installation

**1)** Installation is done by composer: add the following into your main `composer.json` (inside require) and then run `composer update`

```
"hashmode/cakephp-tinymce-elfinder": "~1.0"
```
Elfinder requirement is added inside plugin's composer, so it will automatically install it, however Tinymce should be installed separately: as well as jquery ui, upon which the Elfinder is dependent on.
Because the statics files that are accessed inside the plugin's view files - are required to be in webroot directory, that is why after installation/update composer script based on callback should copy the static files from elfinder's folder to the plugin's webroot, for this reason the following should be added inside application's main `composer.json` file 

```
    "scripts": {
        "post-update-cmd": "CakephpTinymceElfinder\\Console\\Installer::postUpdate"
    },
```

**2)** Load Plugin from `bootstrap.php`
```
Plugin::load('CakephpTinymceElfinder', ['routes' => true]);
```

**3)** Add configuration options into `bootstrap.php` (or you can create another file and include it in bootstrap)
```
Configure::write('TinymceElfinder', array(
    'title' => __('Elfinder File Manager'),
    'client_options' => array(
        'width' => 900,
        'height' => 500,
        'resizable' => 'yes'
    ),
    'static_files' => array(
        'js' => array(
            'jquery' => 'jquery-2.1.4.min.js',
            'jquery_ui' => 'libs/jqueryui/jquery-ui.min.js'
        ),
        'css' => array(
            'jquery_ui' => 'libs/jqueryui/jquery-ui.min.css',
            'jquery_ui_theme' => ''
        )
    ),
    'options' => array(
        // 'debug' => true,
        'roots' => array(
            array(
                'driver' => 'LocalFileSystem',                  // driver for accessing file system (REQUIRED)
                'URL' => Router::url('/uploads', true),         // upload main folder
                'path' => WWW_ROOT . 'uploads',                 // path to files (REQUIRED)
                'attributes' => array(
                    array(
                        'pattern' => '!(thumbnails)!',
                        'hidden' => true
                    )
                ),
                'tmbPath' => 'thumbnails',
                'uploadOverwrite' => false,
                'checkSubfolders' => false,
                'disabled' => array()
            )
        ),
    )
));

```

**client_options:** This is the list of options that is being used initiating the elfinder in javascript.
https://github.com/Studio-42/elFinder/wiki/Client-configuration-options

**static_files:** `Jquery min` and `Jquery UI` are necessary for elfinder to work, so they are being used in the plugin view, however to avoid copying them into plugin's webroot or application's webroot(maybe you are already using them) - it is just omitted and it is required to provide paths to css and js files for jquery min js, jquery ui min js and jquery ui css. Jquery ui theme's css is optional. These files should  reside in your application's webroot directory (or any plugins webroot directory - in that case you should use plugin syntax http://book.cakephp.org/3.0/en/appendices/glossary.html#term-plugin-syntax)

**options:** https://github.com/Studio-42/elFinder/wiki/Client-configuration-options-2.1


**4)** Load Plugin's helper - by adding the following into your `AppView.php` initialize

```
$this->loadHelper('CakephpTinymceElfinder.TinymceElfinder');
```

**5)** Include tinymce into your page (Tinymce is NOT being installed with the plugin)

```
<?php echo $this->Html->script('tinymce/tinymce.min.js'); ?>
```

**6)** By this line it will define a js function for `file_browser_callback` for tinymce
```
<?php $this->TinymceElfinder->defineElfinderBrowser()?>
```

**7)** Tinymce Init

```
$(document).ready(function() {
	// tinymce init
	tinymce.init({
	  file_browser_callback : elFinderBrowser,
	  selector: "textarea",
	  theme: "modern",
	    
	  ... rest of your code
```


## IMPORTANT !! - SECURITY

By default all the commands are allowed - so if you need to allow only specific commands - make sure to add the rest of the commands(that should NOT be allowed) under `disabled` under options' roots.
https://github.com/Studio-42/elFinder/wiki/Client-Server-API-2.1#command-list

There are 2 actions in `ElfindersController.php` controller of the plugin, that are used for the functionality of elfinder, corresponding access permissions should be handled manually from the application.


## License

MIT - Please refer to Elfidner and Tinymce websites for their licenses











