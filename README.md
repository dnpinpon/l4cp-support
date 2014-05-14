![screenshot](http://i.imgur.com/EkP4YaG.png)

# Example Package for LaravelCP
This is an basic ticket system example for LaravelCP. It is intended to showcase how you can extend LaravelCP.

# Features
- E-mail import per department (with spam filter)
- Auto escalations
- Control over departments and notifications

Please note there is no client interface at this time. Clients can e-mail your support department email (if configured) to create tickets.

### Add to your composer.json

      "gcphost/l4cp-support": "dev-master",

### Update

      composer update

### Edit your app/config/app.php

#### Add the the provider:

      'Gcphost\L4cpSupport\L4cpSupportServiceProvider',

#### Add to the alias:

      'Support'	=>	'Gcphost\L4cpSupport\Helpers\Support',
 


### Dump auto load 

     composer dump-autoload
     
### Migrate the database:

     php artisan migrate --path="vendor/gcphost/l4cp-support/src/migrations"
     
     
### Run the installer
- Once installed run the install page from your browser
- admin/support/install
- this will install/update the configuration settings.
- define your email settings in Settings > Support
