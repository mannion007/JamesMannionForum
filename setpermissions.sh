sudo semanage fcontext -a -t 'httpd_cache_t' 'app/cache(/.*)?'
sudo restorecon -Rvvv app/cache

sudo semanage fcontext -a -t 'httpd_cache_t' 'app/logs(/.*)?'
sudo restorecon -Rvvv app/logs

chmod -R 777 app/cache
chmod -R 777 app/logs
