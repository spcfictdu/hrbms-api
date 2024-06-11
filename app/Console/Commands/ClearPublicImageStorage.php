<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ClearPublicImageStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Clear public image storage
    protected $signature = 'storage:clear-public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears public image folders and recreates symbolic link';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Delete public image folders
        File::deleteDirectory(public_path('storage'));

        // Recreate symbolic link
        $this->call('storage:link');

        $this->info('Public image folders cleared and symbolic link recreated successfully.');

        return 0;
    }
}
