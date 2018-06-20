<?php namespace Anhi\Userprofile;

use System\Classes\PluginBase;

use RainLab\User\Controllers\Users;

use RainLab\User\Models\User;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Anhi\Userprofile\Components\UserInfo' => 'userInfo',
            'Anhi\Userprofile\Components\UserSidebar' => 'userSidebar',
            'Anhi\Userprofile\Components\UserNav' => 'userNav'
        ];
    }

    public function registerSettings()
    {
    }

    public function boot ()
    {
    	Users::extendFormFields(function($form, $model, $context) {

    		$form->addTabFields([


                'expire' => [

                    'label' => 'Hạn VIP',

                    'type' => 'datepicker',

                    'tab' => 'Profile',

                ],


			]);

    	});

        User::extend(function($model) {

            $model->attachOne = [
                'avatar' => 'System\Models\File'
            ];

            $model->attachMany = [
                'favouriteMovies' => [
                
                ],
            ];
        });
    }
}
