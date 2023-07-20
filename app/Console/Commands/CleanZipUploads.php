<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanZipUploads extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'storage:cleanZipUploads';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Removes .zip files from uploads that files is older than 1 hour';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle(): void
	{
		$files = Storage::disk('local')->files('zips');
		foreach ($files as $file) {
			$time = Storage::lastModified($file);
			$fileModifiedDateTime = Carbon::parse($time);

			if (now()->gt($fileModifiedDateTime->addHour())) {
				Storage::disk('local')->delete($file);
			}
		}
	}
}
