<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanTempUploadsFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:cleanTempUploadsFolder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes files from temp uploads that files is older than 1 hour';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        $files = Storage::disk('local')->files('tmp_files');
        foreach ($files as $file) {
            $time = Storage::disk('local')->lastModified($file);
            $fileModifiedDateTime = Carbon::parse($time);

            if (Carbon::now()->gt($fileModifiedDateTime->addHour())) {

                Storage::disk("local")->delete($file);
            }
        }

        return true;
    }
}
