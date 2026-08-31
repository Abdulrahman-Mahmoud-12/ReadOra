<?php

namespace App\Console\Commands;

use App\Services\BookImportService;
use Illuminate\Console\Command;

class ImportBooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'readora:import-books {path? : Path to the CSV file (defaults to database/data/books.csv)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import real books metadata and physical copies from a CSV file into the library database';

    /**
     * Execute the console command.
     */
    public function handle(BookImportService $importService): int
    {
        $path = $this->argument('path') ?: database_path('data/books.csv');

        if (! file_exists($path)) {
            $this->error("Target CSV file not found at: [{$path}]");

            return Command::FAILURE;
        }

        $this->info("Starting book import from [{$path}]...");

        $summary = $importService->importFromCsv($path);

        $this->newLine();
        $this->info('--- Import Summary ---');
        $this->line("Books Created / Updated: <info>{$summary['books']}</info>");
        $this->line("Physical Copies Added:   <info>{$summary['copies']}</info>");
        $this->line("Skipped / Invalid Rows:  <comment>{$summary['skipped']}</comment>");

        if (! empty($summary['errors'])) {
            $this->newLine();
            $this->warn('Errors encountered during import:');
            foreach ($summary['errors'] as $error) {
                $this->error("- {$error}");
            }
        }

        $this->newLine();
        $this->info('Book catalog successfully synchronized with MySQL database.');

        return Command::SUCCESS;
    }
}
