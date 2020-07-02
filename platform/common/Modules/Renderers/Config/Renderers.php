<?php namespace Common\Modules\Renderers\Config;

use CodeIgniter\Config\BaseConfig;

class Renderers extends BaseConfig
{
    public $config = [];

    public function __construct()
    {
        parent::__construct();

        $this->config['validDrivers'] = [
            'twig',
            'mustache',
            'handlebars',
            'markdown',
            'textile',
            'markownify',
        ];

        // PHP in views is not allowed as predecessor renderer,
        // if there is a file extension here.
        $this->config['fileExtensions'] = [
            'twig' => ['twig', 'html.twig'],
            'mustache' => 'mustache',
            'handlebars' => ['handlebars', 'hbs'],
            'markdown' => ['md', 'markdown', 'fbmd'],
            'textile' => 'textile',
        ];

        // 'renderer', 'parser'
        $this->config['driverTypes'] = [
            'twig' => 'renderer',
            'mustache' => 'renderer',
            'handlebars' => 'renderer',
            'markdown' => 'parser',
            'textile' => 'parser',
            'markdownify' => 'renderer',
        ];

        $this->config['driverClasses'] = [
            'twig' => '\Common\Modules\Renderers\Twig',
            'mustache' => '\Common\Modules\Renderers\Mustache',
            'handlebars' => '\Common\Modules\Renderers\Handlebars',
            'markdown' => '\Common\Modules\Renderers\Markdown',
            'textile' => '\Common\Modules\Renderers\Textile',
            'markdownify' => '\Common\Modules\Renderers\Markdownify',
        ];
    }

}