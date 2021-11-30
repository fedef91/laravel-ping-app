<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\UserContract;
use App\Repositories\UserRepository;
/*RepositoryContractDeclare*/


class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = 
    [
        UserContract::class => UserRepository::class,
        /*RepositoryContractBind*/

    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation){
            $this->app->bind($interface, $implementation);
        }
    }
}
