<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\hotel;
use App\Hoteldetail;

class ImageUploadScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imageupload:start';

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
       //Hotel table  get picture only script

       $imagedata = hotel::select('picture')->get();
       foreach($imagedata as $val) {
            $img_url = $val->picture;
            if (strpos($img_url, 'yatra') !== false) {
              $base_folder =  "/var/www/biddr_ws/public/images/uploads/yatra/";
           } else {
               $base_folder =  "/var/www/biddr_ws/public/images/uploads/";
           } 
            $url = "https://in.magicspree.com/extranet/$img_url";
             $file = file_get_contents($url);
            $path = $base_folder. basename($url);
             file_put_contents($path, $file);
       }
      
       //HOTEL TABLE  STORE PICTURES SCRIPT

        // $imagedata = hotel::select('pictures')->get();
        // foreach($imagedata as $val) {
        //      $img_url = $val->pictures;
        //      $imgpictures = explode(',',$img_url);
        //       foreach($imgpictures as $values) {
        //         if (strpos($values, 'additional_img') !== false) {
        //             $base_folder =  "/var/www/biddr_ws/public/images/uploads/yatra/additional_img";
        //          } else {
        //              $base_folder =  "/var/www/biddr_ws/public/images/uploads/";
        //          } 

        //      $url = "https://in.magicspree.com/extranet/$values";
        //      $file = file_get_contents($url);
        //       //STORE IMAGE ACCORDING IMAGE FOLDER
        //       $path = $base_folder. basename($url);
        //       file_put_contents($path, $file);
        //     }  
        // }
 
         //HOTELDETAILS TABLE  STORE PICTURE SCRIPT
            // $imagedata = Hoteldetail::select('picture')->get();
            // foreach($imagedata as $val) {
            //     $img_url = $val->picture;
            //     $url = "https://in.magicspree.com/extranet/$img_url";
            //     $file = file_get_contents($url);
            //     $path = "/var/www/biddr_ws/public/images/uploads/". basename($url);
            //     file_put_contents($path, $file);
            // }



    }
}
