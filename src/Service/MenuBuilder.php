<?php

namespace App\Service;

class MenuBuilder
{
    public function getMenuData(): array
    {
        return [
            'list' => [
                'link' => '/companies',
                'icon' => 'fa-building-o',
                'label' => 'List of Companies',
            ],
            [
                'link' => '/invoices',
                'icon' => 'fa-file-o',
                'label' => 'List of Invoices',
            ],
        ];
    }
}
