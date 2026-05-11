#!/bin/bash
rm -rf /var/www/phproot
cp -r ./phproot /var/www/phproot
ln -s /mnt/nas/shared/accessImgs /var/www/phproot/wwwroot/images
