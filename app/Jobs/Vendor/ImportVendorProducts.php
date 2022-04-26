<?php

namespace App\Jobs\Vendor;

use App\Imports\Wave\ProductImport;
use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        if (empty($this->vendor->csv_url)) {
            return view('app.products.import')
                ->with('vendors', Vendor::all())
                ->with('editing', null)
                ->withError(__('crud.products.errors.no_csv_configuration'));
        }

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

        $keys = $csvLines->first();

        $items = $csvLines->skip(1)->all();

        collect($items)->map(function($item) use($keys) {
            if(count($item) === count($keys)) {
                return collect($keys)->combine($item);
            }
        })->each(function($product) {
            if($product) {
                (new ProductImport())->model($product->toArray());
            }
        });
    }
}
