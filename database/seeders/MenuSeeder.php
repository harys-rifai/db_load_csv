<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Parent Menu: Server
        $serverMenu = Menu::create([
            'title' => 'Server',
            'icon' => 'heroicon-o-chart-bar',
            'order' => 1,
        ]);

        // Child Menu: Server Linux Metrics
        Menu::create([
            'title' => 'Server Linux Metrics',
            'icon' => 'heroicon-o-chart-bar',
            'route' => 'system.metrics.graph',
            'parent_id' => $serverMenu->id,
            'order' => 1,
        ]);

        // Parent Menu: User Config
        $userConfigMenu = Menu::create([
            'title' => 'User Config',
            'icon' => 'heroicon-o-cog',
            'order' => 2,
        ]);

        // Child Menu: Profile
        Menu::create([
            'title' => 'Profile',
            'icon' => 'heroicon-o-user',
            'route' => 'profile',
            'parent_id' => $userConfigMenu->id,
            'order' => 1,
        ]);
    }
}
