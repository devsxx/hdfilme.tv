mv ./plugins/anhi/userprofile/updates/version.yaml ./plugins/anhi/userprofile/updates/version.yaml.tmp
php artisan october:up --env=dev
mv ./plugins/anhi/userprofile/updates/version.yaml.tmp ./plugins/anhi/userprofile/updates/version.yaml
php artisan october:up --env=dev