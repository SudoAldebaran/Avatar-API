<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// MODEL DE SEEDER

class SvgSeederModel extends Seeder
{
    public function run(): void
    {
        $svgData = [
            // Backgrounds
            ['element_type' => 'background', 'element_name' => 'background_1', 'svg_content' => ''],
            ['element_type' => 'background', 'element_name' => 'background_2', 'svg_content' => ''],
            ['element_type' => 'background', 'element_name' => 'background_3', 'svg_content' => ''],

            // Barbes
            ['element_type' => 'barbe', 'element_name' => 'barbe_1', 'svg_content' => ''],
            ['element_type' => 'barbe', 'element_name' => 'barbe_2', 'svg_content' => ''],

            // Bouches
            ['element_type' => 'bouche', 'element_name' => 'bouche_1', 'svg_content' => ''],
            ['element_type' => 'bouche', 'element_name' => 'bouche_2', 'svg_content' => ''],
            ['element_type' => 'bouche', 'element_name' => 'bouche_3', 'svg_content' => ''],

            // Cheveux
            ['element_type' => 'cheveux', 'element_name' => 'cheveux_1', 'svg_content' => ''],
            ['element_type' => 'cheveux', 'element_name' => 'cheveux_2', 'svg_content' => ''],

            // Haut
            ['element_type' => 'haut', 'element_name' => 'haut', 'svg_content' => ''],

            // Lunettes
            ['element_type' => 'lunettes', 'element_name' => 'lunettes_1', 'svg_content' => ''],
            ['element_type' => 'lunettes', 'element_name' => 'lunettes_2', 'svg_content' => ''],

            // Nez
            ['element_type' => 'nez', 'element_name' => 'nez_1', 'svg_content' => ''],
            ['element_type' => 'nez', 'element_name' => 'nez_2', 'svg_content' => ''],
            ['element_type' => 'nez', 'element_name' => 'nez_3', 'svg_content' => ''],

            // Accessoires
            ['element_type' => 'accessoire', 'element_name' => 'collier', 'svg_content' => ''],
            ['element_type' => 'accessoire', 'element_name' => 'potara', 'svg_content' => ''],

            // Sourcils
            ['element_type' => 'sourcils', 'element_name' => 'sourcils_1', 'svg_content' => ''],
            ['element_type' => 'sourcils', 'element_name' => 'sourcils_2', 'svg_content' => ''],
            ['element_type' => 'sourcils', 'element_name' => 'sourcils_3', 'svg_content' => ''],

            // Visage
            ['element_type' => 'visage', 'element_name' => 'visage', 'svg_content' => ''],

            // Yeux
            ['element_type' => 'yeux', 'element_name' => 'yeux_1', 'svg_content' => ''],
            ['element_type' => 'yeux', 'element_name' => 'yeux_2', 'svg_content' => ''],
            ['element_type' => 'yeux', 'element_name' => 'yeux_3', 'svg_content' => ''],
        ];

        DB::table('svg_elements')->insert($svgData);
    }
}
