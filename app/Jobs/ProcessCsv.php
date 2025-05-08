<?php
namespace App\Jobs;
use App\Models\CsvData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProcessCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    public $timeout = 3600; 

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        try {
            $upload = \App\Models\Upload::firstOrCreate(
                ['file_name' => basename($this->filePath)],
                ['status' => 'pending', 'created_at' => now()]
            );
            $upload->update(['status' => 'processing']);

            $file = fopen(storage_path('app/' . $this->filePath), 'r');
            
            $header = fgetcsv($file, 0, ",");
            if (!empty($header)) {
                $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]); //Check for BOM headerrrrrr
            }
            $uniqueKeyIndex = array_search('UNIQUE_KEY', $header);
            Log::info('Cleaned CSV header: ' . json_encode($header));

            while ($row = fgetcsv($file, 0, ",")) {
                if (count($row) !== count($header)) {
                    Log::warning("Skipping row with mismatched columns: " . implode(',', $row));
                    continue;
                }
                $data = array_combine($header, $row);
                $cleanedData = array_map(function($value) {
                    return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }, $data);
                CsvData::updateOrCreate(
                    ['UNIQUE_KEY' => $cleanedData['UNIQUE_KEY']],
                    $cleanedData
                );
            }

            fclose($file);
            $upload->update(['status' => 'completed']);
        } catch (\Exception $e) {
            Log::error("Failed to process CSV {$this->filePath}: {$e->getMessage()}");
            $upload->update(['status' => 'failed']);
            throw $e; 
        }
    }
}