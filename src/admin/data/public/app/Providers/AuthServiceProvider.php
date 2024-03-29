<?php

namespace App\Providers;

use App\Department;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('administer', function ($user) {
            $department = session('department');

            if(empty($department) || !Department::find($department)){
                return false;
            }

            if($user->role_id==1 || $user->role_id == 3){
                return true;
            }

            //get user department record
            $admin = $user->departments()->where('department_id',$department)->first()->pivot->department_admin;

            return $admin == 1;
        });

        Gate::define('is-owner', function ($user, $model) {
            return $user->id == $model->user_id;
        });

        Gate::define('is_owner', function ($user, $model) {
            return $user->id == $model->user_id;
        });


        Gate::define('dept_allows', function ($user, $option) {
            $department = session('department');

            if($user->role_id==1 || $user->role_id == 3){
                return true;
            }

            $admin = $user->departments()->where('department_id',$department)->first()->pivot->department_admin;

            if($admin==1){
                return true;
            }

            $dept = getDepartment()->toArray();
            return $dept[$option]==1;
        });

        Gate::define('department_member', function ($user, $model) {
            $deptId = getDepartment()->id;

            return $model->departments()->where('id',$deptId)->count() > 0;

        });


    }
}
