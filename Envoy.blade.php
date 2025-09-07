@servers(['web' => 'ubuntu@54.255.131.115'])

@setup
    $repository = 'git@gitlab.com:e-pourashava/e-poura-api-app.git';
    $releases_dir = '/var/www/e-api/releases';
    $app_dir = '/var/www/e-api';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    run_composer
    update_symlinks
    update_database
    setup_permission
    update_permissions
    clean_cache
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    echo 'Cloning done'
    cd {{ $new_release_dir }}
    echo 'going to ' {{ $new_release_dir }}
    git reset --hard {{ $commit }}
    echo 'Git reset hard done, cloning completed'
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $new_release_dir }}
    echo 'Composer package installing'
    composer install --prefer-dist --no-scripts -q -o
    composer dump-autoload
    echo 'Composer install completed'
@endtask

@task('update_symlinks')
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage
    echo "Linked storage directory"

    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env
    echo "Linked env file"

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
    echo "Linking to " {{ $new_release_dir }} with {{ $app_dir }}/current

    echo "Linking finished"
@endtask

@task('update_database')
    echo "updating databases"

    cd {{ $new_release_dir }}

    php artisan migrate
    echo "database migrated"

    echo "Migrating into sandbox database"
    php artisan migrate --database=sandbox-mysql

    echo "Seeding database"
    php artisan db:seed

    echo "Seeding into sandbox database"
    php artisan db:seed --database=sandbox-mysql
    echo "db seed completed"

@endtask

@task('setup_permission')

    echo "Updating permissions"
    cd {{ $new_release_dir }}
    sudo chown -R $USER:www-data .

    sudo find . -type f -exec chmod 664 {} \;
    sudo find . -type d -exec chmod 775 {} \;
    echo "Updated permissions for the user"

@endtask


@task('update_permissions')
    echo "Updating permission to storage folder"
    cd {{ $new_release_dir }}
    sudo chgrp -R www-data storage bootstrap/cache
    echo "End Updating permission to storage folder"

    echo "Updating permission to bootstrap/cache folder"
    sudo chmod -R ug+rwx storage bootstrap/cache

    echo "Complete permission for bootstrap/cache"
@endtask


@task('clean_cache')
    echo "Cleaning caches"

    cd {{ $new_release_dir }}

    php artisan config:cache
    php artisan cache:clear
    php artisan view:clear

    echo "artisan cache cleared"
    sudo php artisan optimize:clear
    echo "optimized successfully"

    php artisan queue:restart
    echo "Queue restarted"

    echo "linking storage"
    php artisan storage:link

    echo "Job finished successfully"
@endtask
