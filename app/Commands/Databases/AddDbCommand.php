<?php

namespace App\Commands\Databases;

use App\CliHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class AddDbCommand extends Command
{
    use CliHelper;

    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'db:create {dbname}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates the database.';

    /**
     * Execute the command. Here goes the code.
     *
     * @return void
     */
    public function handle(): void
    {
        $pwd = getcwd();
        $ldkPath = dirname($pwd) . DIRECTORY_SEPARATOR . '.ldk' . DIRECTORY_SEPARATOR;
        $projectFolderBaseName = basename($pwd);

        if (! File::exists($ldkPath)) {
            $this->error('You have not configured Laradock yet.');
        } else {
            $dbName = $this->argument('dbname');

            $this->info("Creating database '{$dbName}' on mysql...");
            $this->runQuietly("cd {$ldkPath} && docker-compose exec mysql mysql -u root -proot -e \"create database {$dbName}; GRANT ALL PRIVILEGES ON $dbName.* TO 'default'@'%'\"");
            $this->info('Done!');
        }
    }
}
