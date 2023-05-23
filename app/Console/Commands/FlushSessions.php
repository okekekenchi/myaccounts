<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FlushSessions extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'session:flush';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Flush all user sessions';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
			parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$driver = Str::lower(config('session.driver'));
		$method_name = "flush_$driver";

		if (method_exists( $this, $method_name)) {
			try {
				$this->$method_name();
				$this->info('Session flushed!');
			} catch (\Exception $ex) {
				$this->error($ex->getMessage());
			}
		}
		else {				
			$this->error("Session flush has not been implemented for this driver");
		}
	}

	protected function flush_database()
	{
		DB::table( config('session.table') )->truncate();
	}

	protected function flush_file()
	{
		$directory = config('session.files');
		$ignore_files = [ '.gitignore', '.', '..'];

		$files = scandir($directory);

		foreach ($files as $file) {
			if (!in_array($file, $ignore_files)) {
				unlink($directory.'/'.$file);
			}
		}
	}
}
