<?php

namespace AcquaintSofttech\StataMailer\Services;

use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class DotEnvService {

    public function writeToEnv(array $newVals){
        $envString = file_get_contents(base_path('.env'));

        $lines = explode("\n", $envString);
        foreach ($lines as $line) {
            try {
                $envVals = parse_ini_string($line);
            } catch (\Throwable $th) {
                $envVals = null;
            }
            if ($envVals) {
                foreach ($envVals as $key => $value) {
                    if (array_key_exists($key, $newVals)) {
                        // $envString = str_replace($key . '=' . $value, $key . '=' . $newVals[$key], $envString);

                        $newVal = $newVals[$key];
                        if (preg_match('/\s/', $newVal)) { // matches any whitespace character
                            $newVal = '"' . $newVal . '"';
                        }

                        $envString = preg_replace('/^' . $key . '=(.*)$/m', $key . '=' . $newVal, $envString);
                    }
                }
            }
        }

        $envFile = fopen(base_path('.env.local'), 'w');

        fwrite($envFile, $envString . PHP_EOL);
        fclose($envFile);
    }

    public function readFromEnv(){
        $envString = file_get_contents(base_path('.env'));

        $lines = explode("\n", $envString);
        $envVals = [];
        foreach ($lines as $line) {
            try {
                $envVal = parse_ini_string($line);
                $envVals = array_merge($envVals, $envVal);
            } catch (\Throwable $th) {
                Log::error($th->getMessage());

            }
        }

        return $envVals;
    }
}

