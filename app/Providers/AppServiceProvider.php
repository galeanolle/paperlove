<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Product;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
        $this->app->bind('path.public',function(){
            return base_path().'/public_html';
        });
        */
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

     view()->share('categoryList', $this->getCategoryList());
    }

    private function getCategoryList(){
        $categoriesParent=DB::table('categories')->where('id_parent',0)->get();

        $categoryList = array();

        foreach ($categoriesParent as $categoryParent){

            $arr = array($categoryParent->id,$categoryParent->id_parent,$categoryParent->name,$categoryParent->slug);
            array_push($categoryList,$arr);

            $categories=DB::table('categories')->where('id_parent',$categoryParent->id)->get();

            foreach ($categories as $category){
                $arr = array($category->id,$category->id_parent,$category->name,$categoryParent->slug,$category->slug);
                array_push($categoryList,$arr);

            }

        }
        return $categoryList;
    }
}
