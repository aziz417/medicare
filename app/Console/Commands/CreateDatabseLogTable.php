<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabseLogTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:activity:table {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create database activity log table. use `php artisan activity:table up` for create & use `php artisan activity:table down` for remove.";
    
    /**
     * Activity Table Name
     *
     * @var string
     */
    protected $tableName = "activity_logs";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->tableName = config('system.activity.table', 'activity_logs');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if( $this->argument('action') == 'up' ){
            $this->createTable();
        }elseif( $this->argument('action') == 'down' ){
            $this->removeTable();
        }
        return 0;
    }

    protected function createTable()
    {
        if( Schema::hasTable($this->tableName) ){
            $this->info("[{$this->tableName}] table already migrated!");
            return;
        }

        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('BASIC');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('content')->nullable();
            $table->text('additional')->nullable();
            $table->timestamp('created_at')->nullable();
        });
        $this->info("[{$this->tableName}] table created successfully!");
    }

    protected function removeTable()
    {
        Schema::dropIfExists($this->tableName);
        $this->info("[{$this->tableName}] table removed successfully!");
    }
}
