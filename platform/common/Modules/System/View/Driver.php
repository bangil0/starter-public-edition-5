<?php

namespace Common\Modules\System\View;

class Driver
{
    public static function validDrivers()
    {
        // TODO: Get this from a configuration file.
        $result = [
//            'parser',
            'twig',
//            'handlebars',
//            'mustache',
//            'markdown',
//            'textile',
        ];

        return $result;
    }

    public static function isRenderer($driver)
    {
        // TODO: Get this from a configuration file.
        $renderers = [
            'parser',
            'twig',
            'handlebars',
            'mustache',
        ];

        return in_array($driver, $renderers);
    }

    public static function getFileExtensions($driverName = null)
    {
        static $extensions = null;

        if ($extensions === null) {

            $extensions = [];

            // TODO: Get this from a configuration file.
            $configuredExtensions = [
                'parser' => 'tpl',
                'twig' => ['twig', 'html.twig'],
                'handlebars' => ['handlebars', 'hbs'],
                'mustache' => 'mustache',
                'markdown' => ['md', 'markdown', 'fbmd'],
                'textile' => 'textile',
            ];

            $validDrivers = static::validDrivers();

            foreach ($configuredExtensions as $key => $value) {

                if (in_array($key, $validDrivers)) {
                   continue;
                }

                if (!is_array($value)) {
                    $value = (array) $value;
                }

                foreach ($value as & $item) {
                    $item = ltrim($item, '.');
                }

                unset($item);

                $extensions[$key] = $value;
            }
        }

        $driverName = (string) $driverName;

        if ($driverName == '') {

            return $extensions;
        }

        return isset($extensions[$driverName]) ? $extensions[$driverName] : [];
    }

    public static function getDriversByFileExtensions()
    {
        static $drivers = null;

        if ($drivers === null) {

            $drivers = [];
            $allExtensions = static::getFileExtensions();

            foreach ($allExtensions as $driverName => $extensions) {

                foreach ($extensions as $extension) {
                    $drivers[$extension] = $driverName;
                }
            }
        }

        return $drivers;
    }

    public static function parseOptions($options)
    {
        if (!is_array($options)) {

            $options = (string) $options;
            $options = $options != '' ? [$options] : [];
        }

        if (empty($options)) {

            return $options;
        }

        $drivers = [];
        $result = [];

        foreach ($options as $key => $value) {

            if (is_string($key)) {

                if (in_array($key, static::validDrivers())) {

                    $drivers[] = ['name' => $key, 'type' => static::isRenderer($key) ? 'renderer' : 'parser', 'options' => $value];

                } else {

                    $result[$key] = $value;
                }

            } elseif (is_string($value)) {

                if (in_array($value, static::validDrivers())) {

                    $drivers[] = ['name' => $value, 'type' => static::isRenderer($value) ? 'renderer' : 'parser', 'options' => []];

                } else {

                    $result[] = $value;
                }
            }
        }

        if (!empty($drivers)) {

            $result['drivers'] = $drivers;
        }

        return $result;
    }

}
