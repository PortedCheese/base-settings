<?php

namespace PortedCheese\BaseSettings\Console\Commands;

use App\ImageFilter;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PortedCheese\BaseSettings\Models\LoginLink;

class ImageFiltersClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image-filters:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear image_filters table & filters folder';

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
     */
    public function handle()
    {
        $filters = ImageFilter::query()->get();
        try {
            foreach ($filters as $item){
                $img = $item->image;
                if ($img)
                    $img->filtersClear();
                else{
                    Storage::delete($item->path);
                    $item->delete();
                }
            }
        }
        catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
