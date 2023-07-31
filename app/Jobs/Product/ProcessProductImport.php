<?php

namespace App\Jobs\Product;

use App\Imports\Wave\ProductImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProductImport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $keys,
        protected array $item
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new ProductImport())->model(array_combine($this->keys, $this->item));
    }
}
