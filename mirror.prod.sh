if [ -f "./public/uploader" ]
then
	mv ./public/uploader ./uploader
fi
rm -rf public
/usr/local/php-fpm-5.6/bin/php artisan cache:clear --env=production
/usr/local/php-fpm-5.6/bin/php artisan october:mirror --relative public --env=production
/usr/local/php-fpm-5.6/bin/php artisan optimize --env=production
/usr/local/php-fpm-5.6/bin/php artisan config:cache --env=production
/usr/local/php-fpm-5.6/bin/php artisan route:cache --env=production

if [ -f "./uploader" ]
then
	mv ./uploader ./public/uploader
fi

rm /var/www/html/v2.hdfilme.tv/public/ads.txt
ln -s /var/www/html/v2.hdfilme.tv/ads.txt /var/www/html/v2.hdfilme.tv/public/ads.txt