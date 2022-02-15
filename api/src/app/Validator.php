<?php

namespace App\app;

use Illuminate\Validation\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator as IlluminateTranslator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Database\Capsule\Manager as DB;

class Validator
{
    public static function make($request, $rules, $messages, $capsuleContainer)
    {
        $languagePath = __DIR__ . '/../resources/lang';
        $languageDefault = 'en';
			  
        $loader = new FileLoader(new Filesystem(), $languagePath);
        $translator = new IlluminateTranslator($loader, $languageDefault);
		$validation = new Factory($translator);
		$presence = new DatabasePresenceVerifier($capsuleContainer->getDatabaseManager());
		$validation->setPresenceVerifier($presence);

        return $validation->make($request->getParams(), $rules, $messages);
    }
}
