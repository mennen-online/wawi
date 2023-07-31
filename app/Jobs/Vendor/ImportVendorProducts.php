<?php

namespace App\Jobs\Vendor;

use App\Imports\Wave\ProductImport;
use App\Jobs\Product\ProcessProductImport;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\VendorProduct\ImportSuccessfulNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ImportVendorProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Vendor $vendor
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = Http::withBasicAuth($this->vendor->username, $this->vendor->password)->get($this->vendor->csv_url);

        $path = 'csv/'.$this->vendor->id.'/file.csv';

        Storage::put($path, $request->body());

        $string = str_replace([
            "\n",
        ], [
            "\\"
        ], $request->body());

        $array = explode("\\", $string);

        $csvLines = collect($array)->map(function ($line) {
            return explode("\t", $line);
        });

        $keys = $csvLines->shift();

        $csvLines
            ->filter(function($item) use($keys) {
                if(count($item) === count($keys)) {
                    return $item;
                }
            })
            ->each(function($item) use($keys) {
            if(count($item) === count($keys)) {
                ProcessProductImport::dispatch($keys, $item);
            }
        });
    }
}
