# Benleneve_Offer for Magento 2

- **Requires at least:** 2.3
- **Tested up to:** 2.4
- **Requires PHP:** 7.3
- **Stable tag:** 1.0.0
- **License:** OSL-3.0
- **License URI:** https://opensource.org/licenses/OSL-3.0

## Overview

This plugin allows you to add banner advertising on your product pages.

- Possibility to display an offer according to one or more categories
- Adding an image and text for each offer
- Choice of the validity date of the offer
- Possibility to temporarily deactivate an offer

## Plugin installation via Github

Follow the instruction below if you want to install Benleneve_Offer for Magento 2 using Git.

1.) Clone the git repository in the Magento 2 `app/code` folder using:

    git clone git@github.com:benleneve/Offer.git Benleneve/Offer

2.) Set the correct directory permissions:

    chmod -R 755 app/code/Benleneve/Offer

Depending on your server configuration, it might be necessary to set whole write permissions (777) to the files and folders above.
You can also start testing with lower permissions due to security reasons (644 for example) as long as your php process can write to those files.

3.) Connect via SSH and run the following commands (make sure to run them as the user who owns the Magento files!)

    php bin/magento module:enable Benleneve_Offer
    php bin/magento maintenance:enable
    php bin/magento setup:upgrade
    php bin/magento setup:static-content:deploy
    php bin/magento maintenance:disable

4.) Go to "System" > "Cache Management" and click both the "Flush Magento Cache" as well as the "Flush Cache Storage" button. This is required to activate the extension.

## Plugin installation via Composer

Follow the instruction below if you want to install Benleneve_Offer for Magento 2 using Composer.

    composer require benleneve/module-offer
    php bin/magento module:enable Benleneve_Offer
    php bin/magento setup:upgrade
