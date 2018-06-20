mv ./plugins/anhi/userprofile/updates/version.yaml ./plugins/anhi/userprofile/updates/version.yaml.tmp
/usr/local/php-fpm-5.6/bin/php artisan october:up --env=production
mv ./plugins/anhi/userprofile/updates/version.yaml.tmp ./plugins/anhi/userprofile/updates/version.yaml
/usr/local/php-fpm-5.6/bin/php artisan october:up --env=production