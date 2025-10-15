<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

// CONTROLLER POUR RECUPERER LES ÉLEMENTS SVG DEPUIS LA BASE DE DONNÉES A PARTIR DE L'API

class SvgElementController extends Controller
{
    public function index(): JsonResponse
    {
        // RÉCUPÈRE TOUS LES ÉLÉMENTS SVG DE LA TABLE svg_elements
        $svgElements = DB::table('svg_elements')->get();

        return response()->json($svgElements);
    }
}
