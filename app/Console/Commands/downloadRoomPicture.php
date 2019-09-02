<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\hotel;
use App\Hoteldetail;

class downloadRoomPicture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloadroompicture:start';

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
        $imagedata = Hoteldetail::select('picture')->get();
            foreach($imagedata as $val) {
                $img_url = $val->picture;
                $url = "https://in.magicspree.com/extranet/$img_url";
                $file = file_get_contents($url);
                $path = "/home/spriimagi/public_html/biddr_mex/public/images/uploads/". basename($url);
                file_put_contents($path, $file);
            }
            echo "Room picture moved";
    }
}
