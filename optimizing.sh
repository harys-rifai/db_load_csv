OPTIMASI SERVER APACHE UNTUK LARAVEL + TAILWIND + LIVEWIRE

1. ✅ Aktifkan HTTP/2 (CentOS/RHEL)
   - Install modul:
     sudo dnf install mod_http2
   - Tambahkan ke VirtualHost HTTPS:
     <VirtualHost *:443>
         Protocols h2 http/1.1
         ...
     </VirtualHost>
   - Restart Apache:
     sudo systemctl restart httpd

2. ✅ Aktifkan Gzip Compression
   Tambahkan ke konfigurasi Apache:
   <IfModule mod_deflate.c>
       AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
   </IfModule>

3. ✅ Aktifkan Caching Static Assets
   <IfModule mod_expires.c>
       ExpiresActive On
       ExpiresByType text/css "access plus 1 month"
       ExpiresByType application/javascript "access plus 1 month"
       ExpiresByType image/jpeg "access plus 1 year"
   </IfModule>

⚙️ OPTIMASI LARAVEL

1. Cache konfigurasi dan route:
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

2. Aktifkan OPcache di php.ini:
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.max_accelerated_files=10000
   opcache.validate_timestamps=0

🎨 OPTIMASI TAILWIND

1. Pastikan purge aktif di tailwind.config.js:
   content: ['./resources/**/*.blade.php', './resources/**/*.js']

2. Build untuk produksi:
   npm run build

⚡ OPTIMASI LIVEWIRE

1. Gunakan wire:key, wire:ignore, wire:model.lazy
2. Gunakan wire:init untuk defer
3. Offload proses berat ke queue

🚨 SOLUSI ERROR: Permission Denied

1. Set kepemilikan folder:
   sudo chown -R apache:apache /var/www/html/dba/storage /var/www/html/dba/bootstrap/cache

2. Set izin folder:
   sudo chmod -R 775 /var/www/html/dba/storage /var/www/html/dba/bootstrap/cache

3. Jika SELinux aktif:
   sudo chcon -R -t httpd_sys_rw_content_t /var/www/html/dba/storage
   sudo chcon -R -t httpd_sys_rw_content_t /var/www/html/dba/bootstrap/cache

4. Restart Apache:
   sudo systemctl restart httpd