<?php

declare(strict_types=1);

namespace App\Core;

use Smarty\Smarty;

class View
{
    private Smarty $smarty;

    public function __construct(
        private readonly array $appConfig
    ) {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(base_path('templates'));
        $this->smarty->setCompileDir(base_path('var/cache/templates_c'));
        $this->smarty->setCacheDir(base_path('var/cache/smarty'));
        $this->smarty->setForceCompile((bool) ($this->appConfig['debug'] ?? false));
        $this->smarty->assign('appName', $this->appConfig['name']);
        $this->smarty->assign('appUrl', $this->appConfig['url']);
    }

    public function render(string $template, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        return $this->smarty->fetch($template);
    }
}
