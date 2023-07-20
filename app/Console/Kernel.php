<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
	protected $commands = ['App\Console\Commands\CleanZipUploads'];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule): void
	{
		$schedule->command('storage:cleanZipUploads')->everyTwoHours();
		Log::info('Zip files for old uploads cleared!');
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands(): void
	{
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
