![screenshot](http://i.imgur.com/EkP4YaG.png)

### Download to workbench/gcphost/l4cp-support 

### Edit your app/config/app.php

#### Add the the provider:

      'Gcphost\L4cpSupport\L4cpSupportServiceProvider',

#### Add to the alias:

      'Support'	=>	'Gcphost\L4cpSupport\Helpers\Support',
 

###Edit your composer.json

#### Add to psr-0

     "Gcphost\\L4cpSupport": "workbench/gcphost/l4cp-support/src/"
    
### Dump auto load in the workbench/gcphost/l4cp-support folder

     cd workbench/gcphost/l4cp-support
     composer dump-autoload

### Run the installer
Once installed run the install page from your browser, its admin/support/install - this will install/update the configuration settings.
