<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function apiKeys() {
      return $this->hasOne("App\ApiKeys");
    }

    public function etsyAuth() {
      return $this->hasOne("App\EtsyAuth");
    }

    public function getCurrentSubscription() {
      return $this->subscription("basic-access");
    }

    public function getCurrentStripePlanName() {
      return $this->getCurrentSubscription()->stripe_plan;
    }

    public function onGracePeriodDefaultSubscription() {
      $subscription = $this->getCurrentSubscription();
      if(isset($subscription)) {
        return $subscription->onGracePeriod();
      }
      return null;
    }

}
