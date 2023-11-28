<?php

namespace App\CustomOverrides;

use Illuminate\Support\Str;

class Translator extends \Illuminate\Translation\Translator
{
    protected function makeReplacements($line, array $replace)
    {
        if (empty($replace)) {
            return $line;
        }

        $shouldReplace = [];

        foreach ($replace as $key => $value) {
            if (is_object($value) && isset($this->stringableHandlers[get_class($value)])) {
                $value = call_user_func($this->stringableHandlers[get_class($value)], $value);
            }

            $shouldReplace['{'.Str::ucfirst($key ?? '').'}'] = Str::ucfirst($value ?? '');
            $shouldReplace['{'.Str::upper($key ?? '').'}'] = Str::upper($value ?? '');
            $shouldReplace['{'.$key.'}'] = $value;
        }

        return strtr($line, $shouldReplace);
    }
}
