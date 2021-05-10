<?php


namespace KeyHoang\PhpConsulEnv;


use Illuminate\Support\ServiceProvider;

class ConsulEnvServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $consulEnv = new ConsulEnv(env('CONSUL_DOMAIN'),env('CONSUL_PATH'),env('CONSUl_TOKEN'));
        $consulEnv->createEnvConsulFile();

        (new \Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
            dirname(dirname(__DIR__)),
            '.env.consul'
        ))->bootstrap();
    }
}