# Highly remastered OpenCart v1.5

This engine is highly remastered OpenCart v1.5.6.4 with a lot of integrations and improvements. Runs on PHP 8.1

Requirements
------------

*   Now working on PHP 8.1.0. Lower versions support is now impossible due to usage of composer libs with 8.1+ requrements. 
*   PHP version higher than 8.1.27 is not tested, but trying peridically to upgrade it
*   Redis cache engine and Redis PHP module (or use Empty cache engine)
*   ElasticSearch to use smart search
*   Ioncube Loader, if you want to use encoded modules
*   Licenses for encoded modules, if you want to use them

Installation (not full, in deveplopment)
------------

* ./cli.php catalog config.php install to create directories
* composer install at root directory to install all composer dependencies
* npm install in js directory to install all js dependencies
* import database structure and fill settings


Information
------------

🚀Running on PHP 8.1, excluding IonCube protected modules (needs special nginx/apache configuration)

❤️Full WEBP support, Full AVIF support via Imagick or in future, when would be implemented, GD

💡Using Redis and flat HTML Caching with afterload info blocks updates. Down to 0.1 seconds TTFB in caching mode with 400k products

💡Smart search via ElasticSearch integrated

🤣By the way, contains a lot of shit-code, because of being one-business specific for a long time.

✨A lot of API's integrated

😍Template supports PWA, and asset caching with service worker

🔥Separate API mode and CLI mode with own configs and route mappings

😊And many-many other changes
