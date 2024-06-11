<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageFolderAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:folder-access {folderName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing the folder access permissions to 755 of a specific subfolder or all subfolders in the storage folder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folderName = $this->argument('folderName');

        if ($folderName) {
            $this->info("Changing the folder access permissions to 755 of the '$folderName' folder in storage folder.");
            $path = storage_path("app/public/$folderName");

            if (is_dir($path) && basename($path) !== 'logs') {
                chmod($path, 0755);
                $this->info("Folder access permissions changed to 755 for '$folderName'.");
            } else {
                $this->error("The specified folder '$folderName' does not exist or is the 'logs' folder.");
            }
        } else {
            $this->info('Changing the folder access permissions to 755 of subfolders in storage folder.');

            // Get the subfolders in the storage folder
            $subfolders = array_filter(glob(storage_path('app/public/*'), GLOB_ONLYDIR), function ($folder) {
                return basename($folder) !== 'logs';
            });

            $this->info('Subfolders in storage folder: ' . implode(', ', $subfolders));

            // Change the folder access permissions to 755
            foreach ($subfolders as $subfolder) {
                chmod($subfolder, 0755);
            }

            $this->info('Folder access permissions changed to 755 of subfolders in storage folder.');
        }
    }
}
