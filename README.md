# MedicsBD Telemedicine

## Installation 

1. Clone the project
2. Run `composer install` 
3. Run `npm install` 
4. Run `cp .env.example .env` && `php artisan key:generate`
5. Configure the application by editing `.env` file
6. Run `php artisan migrate --seed`
7. Run `npm run dev` or `npm run prod`
8. Run `laravel-echo-server init` and set the required info - You need to install echo-server by `npm install -g laravel-echo-server`
9. Run `pm2 start pm2-echo-server.json` - You need to install pm2 by `npm install -g pm2`
10. Run `pm2 start pm2-queue.yml` - You need to install pm2 by `npm install -g pm2`
11. Add `* * * * * php /Users/saiful/Personal/medicsbd/artisan schedule:run 1>> /dev/null 2>&1` to crontab by `sudo crontab -u saiful -e` // to use nano as crontab editor `export VISUAL=nano; crontab -e`

Now your application is ready.

## Resources
For design used "MedicApp" template from Envato. The purchase code is included to the `/FILES` directory.  
For the development used popular PHP framework Laravel with necessary packages.
For the real-time architecture used react with socket and redis.

## Copyright
All right reserved to @msa-rakib