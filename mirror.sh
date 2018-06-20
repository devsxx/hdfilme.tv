if [ -f "./public/uploader" ]
then
	mv ./public/uploader ./uploader
fi

rm -rf public
/usr/local/php-fpm-5.6/bin/php artisan cache:clear --env=staging
/usr/local/php-fpm-5.6/bin/php artisan october:mirror --relative public --env=staging
/usr/local/php-fpm-5.6/bin/php artisan optimize --env=staging
/usr/local/php-fpm-5.6/bin/php artisan config:cache --env=staging
/usr/local/php-fpm-5.6/bin/php artisan route:cache --env=staging


if [ -f "./uploader" ]
then
	mv ./uploader ./public/uploader
fi