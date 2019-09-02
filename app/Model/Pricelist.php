<?php 

  namespace App\Model;
  use Illuminate\Database\Eloquent\Model;
  
  class Pricelist extends Model
  {
    
    protected $table = 'pricelists';
    protected $fillable = ['min_price', 'max_price'];
    protected $hidden = ['created_at', 'updated_at'];




       public static function getPriceList()
                    {
                        $data = Pricelist::orderBy('id','asc')->get();
                        // echo $data;

                        // dd($data);
                        // $data = DB::table('pricelists')->where('id', 1)->get();

                        $data_new = array();
                        foreach ($data as $key => $value) 
                         {
                            // $data_new[$value->id] = $value->min_price.' - '.$value->max_price;
                            $data_new[$value->id] =  $value->display_price; 
                         }

                        // dd ($data_new);

                        return $data_new;

                    }



                    public static function getPriceListRoomType()
                    {
                        $data = Pricelist::orderBy('id','asc')->get();
                        $data_new = array();
                        foreach ($data as $key => $value) 
                         {
                            
                            $data_new[$value->min_price] =  $value->min_price; 
                         }
                        return $data_new;

                    }

  }
