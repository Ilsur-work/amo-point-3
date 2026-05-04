<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseService
{
    private static $capsule;

    public static function init(): void
    {
        self::$capsule = new Capsule;

        self::$capsule->addConnection([
            'driver' => 'sqlite',
            'database' => dirname(__DIR__, 2) . '/database/database.sqlite',
            'prefix' => '',
        ]);

        self::$capsule->setAsGlobal();
        self::$capsule->bootEloquent();

        self::createTables();
    }

    private static function createTables(): void
    {
        if (!Capsule::schema()->hasTable('visits')) {
            Capsule::schema()->create('visits', function ($table)
            {
                $table->id();
                $table->string('visitor_id');
                $table->string('ip')->nullable();
                $table->string('city')->nullable();
                $table->string('device')->nullable();
                $table->string('browser')->nullable();
                $table->string('os')->nullable();
                $table->string('page_url')->nullable();
                $table->string('referrer')->nullable();
                $table->timestamp('visit_time')->useCurrent();
                $table->integer('hour');
                $table->date('date');
                $table->index(['date', 'hour']);
                $table->index('city');
                $table->index('visitor_id');
            });
        }

        if (!Capsule::schema()->hasTable('users')) {
            Capsule::schema()->create('users', function ($table)
            {
                $table->id();
                $table->string('username')->unique();
                $table->string('password_hash');
            });

            Capsule::table('users')->insert([
                'username' => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT)
            ]);
        }
    }

    public static function getCapsule()
    {
        return self::$capsule;
    }
}