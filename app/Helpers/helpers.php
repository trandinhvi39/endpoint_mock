<?php

/**
 * Translate string from key
 *
 * @param string $key
 * @param array $params
 * @param string $locale, default is en (english)
 * @return string was translated
 */
if (!function_exists('translate')) {
    function translate($key, $params = [], $locale = 'en')
    {
        $filePath = resource_path('/lang/' . $locale . '.json');

        if (!is_readable($filePath)) {
            return false;
        }

        $fileJson = file_get_contents($filePath);
        $dataJson = json_decode($fileJson, true);
        $partKeys = explode('.', $key);

        foreach ($partKeys as $partKey) {
            if (!isset($dataJson[$partKey])) {
                return $key;
            }
            $dataJson = $dataJson[$partKey];
        }

        if ($params) {
            foreach ($params as $key => $value) {
                $dataJson = str_replace(':' . $key, $value, $dataJson);
            }
        }

        return $dataJson;
    }
}
