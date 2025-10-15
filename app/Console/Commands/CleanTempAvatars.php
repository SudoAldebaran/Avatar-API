<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTempAvatars extends Command
{
    protected $signature = 'avatars:clean';

    protected $description = 'Supprime les fichiers SVG temporaires de plus de 24h dans temp_avatars/';

    public function handle()
    {
        $deletedCount = 0;

        $files = Storage::disk('public')->files('temp_avatars');

        $this->info("🔍 Analyse des fichiers dans temp_avatars/ ...");

        foreach ($files as $file) {
            // Ignorer les fichiers non-SVG
            if (!str_ends_with($file, '.svg')) {
                continue;
            }

            $lastModified = Storage::disk('public')->lastModified($file);
            $ageInHours = Carbon::createFromTimestamp($lastModified)->floatDiffInHours(now());

            $this->line("- $file (modifié il y a " . round($ageInHours, 2) . " heure(s))");

            if ($ageInHours > 24) {
                Storage::disk('public')->delete($file);
                $this->line("✅ Supprimé !");
                $deletedCount++;
                
            }
        }

        $this->info("🧹 $deletedCount fichier(s) supprimé(s) de temp_avatars/");
        return Command::SUCCESS;
    }
}
