# Git repository of Domopolis.com.ua project
------------

This engine is highly remastered OpenCart v1.5.6.4 with a lot of integrations and improvements. Runs on PHP 8.1

Requirements
------------

*   PHP >= 7.4.0
?   Ioncube Loader, if you want to use encoded modules
?   Licenses for encoded modules, it you want to use them

Composer
------------

* You need to run composer install to download all libraries


Information
------------

🚀Running on PHP 8.1, excluding IonCube protected modules (needs special nginx/apache configuration)

❤️Full WEBP support, Full AVIF support via Imagick or in future, when would be implemented, GD

💡Using Redis (or other key-value storage engine) and flat HTML Caching with afterload info blocks updates. Down to 0.1 seconds TTFB in caching mode with 400k products

💡Smart search via ElasticSearch integrated

🤣By the way, contains a lot of shit-code, because of being one-business specific for a long time.

✨A lot of API's integrated

😍Template supports PWA, and asset caching with service worker

🔥Separate API mode and CLI mode

😊And many-many other changes
