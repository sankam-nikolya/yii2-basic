<?php

return [
    'adminEmail' => function ($app) {
        return $app->keyStorage->get('app.admin.mail');
    },
    'robotEmail' => function ($app) {
        return $app->keyStorage->get('app.robot.email');
    },
    'availableLocales'=>[
        'en'=>'English',
        'ru'=>'Русский',
    ],
    'defaultLanguage' => 'ru'
];
