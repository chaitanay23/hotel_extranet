<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\hotel;
use App\Hoteldetail;

class downloadPictures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloadpictures:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        echo "Downloading pictures";
        $imagedata = hotel::select('pictures','display_name')->get();
        if ($imagedata == null)
        {
            echo "Image data is null";
        }
        $count=count($imagedata);
        echo "Going to start copying $count";
        $i=0;
        foreach($imagedata as $val) {
            $img_url = $val->pictures;
            $imgpictures = explode(',',$img_url);
            foreach($imgpictures as $values) {
                if (strpos($values, 'additional_img') !== false) {
                    $base_folder =  "/home/spriimagi/public_html/biddr_mex/public/images/uploads/yatra/additional_img/";
                    } 
                else {
                    $base_folder =  "/home/spriimagi/public_html/biddr_mex/public/images/uploads/";
                }
                $url = "https://in.magicspree.com/extranet/$values";
                $file = @file_get_contents($url);
                //STORE IMAGE ACCORDING IMAGE FOLDER
                $path = $base_folder. basename($url);
                file_put_contents($path, $file);
            }
            $i=$i+1;
            $per_complete=$i/$count;
            echo "copied $val->display_name $per_complete";
        }
        echo "Done copying";
    }
}