<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Contracts\Auth\Authenticatable;

class ProfileComposer
{
    /**
     * The authenticated Client
     *
     * @var  \App\Model\User
     */
    protected $user;
    
    /**
     * Create a new authenticated composer.
     *
     * @param  null|\Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function __construct(Authenticatable $user = null)
    {
        $this->user = $user;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('auth', $this->user);
    }
}