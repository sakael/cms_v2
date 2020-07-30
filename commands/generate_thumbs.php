<?php
require __DIR__ . '/../config.inc.php';

$thumbs=array();
//preparing data
$data=array('type'=>$argv[1],'model'=>ucfirst($argv[2]));
if(isset($argv[3]) && $argv[3] != ''){
    $data['name']=$argv[3];
}else{
    $data['name']='';
}
if($data['type']=='one'){
    if(!is_numeric($argv[4])){
        echo("\033[31m Please provide numbers for the id \033\n");
        die();
    }
    $data['id']=$argv[4];
}elseif($data['type']=="from"){
    if(!is_numeric($argv[4]) || !is_numeric($argv[5])){
        echo("\033[31m Please provide numbers 'from' 'to' \033\n");
        die();
    }
    $data['from']=$argv[4];
    $data['to']=$argv[5];
}

$classname='App\Classes\\'.$data["model"];
if($data['model'] && (class_exists($classname) || $data['model']=='Type_category')){
    if($data['model']=='Type_category')$data["model"]='Type';
    $classname='App\Classes\\'.$data["model"];
    $model=new $classname();
    $arr=array_column($model::$thumbs,'name');
    if(in_array($data['name'],$arr)){
        $key = array_search($data['name'], array_column($model::$thumbs, 'name'));
        $thumbs[]=$model::$thumbs[$key];
    }else if($data['name']=='all'){
        $thumbs=$model::$thumbs;
    }else if($data['type']!='delete'){
        echo("\033[31m Please provide thumb name which is exist \033\n");
        die();
    }
}else{
    echo("\033[31m Please provide model name which is exist \033\n");
    die();
}
//# preparing data

switch (ucfirst($argv[2])){
    case 'Product':
        productModel($data,$model,$thumbs);
    break;
    case 'Type_category':
        typeCategoryModel($data,$model,$thumbs);
    break;
    default :
    allModels($data,$model,$thumbs);
}

function productModel($data,$model,$thumbs){
    if($data['model']){
        switch($data['type']){
            case 'one': 
                $arrTmp=array();
                $product =new \App\Classes\Product();
                $product = DB::queryOneColumn("id","SELECT id FROM ".$product->getTableName()." where id =%i",$data['id']);
                if(!$product){
                    echo("\033[31m Please provide product ID which exists \033\n");
                    die();
                }
                $productModel=fetchProductImages($product);
                generateProduct($productModel,$model::$imageFolder,$thumbs);
            break;
            case 'from': 
                $products = DB::queryOneColumn("id", "SELECT * FROM ".$product->getTableName()." where id >=%i and id <= %i order by id",$data['from'],$data['to']);
                $productModel=fetchProductImages($products);
                echo("\033[32m The thumbs are generating for ".count($products)." products \033\n");
                generateProduct($productModel,$model::$imageFolder,$thumbs);
            break;
            case 'all': 
                $product =new \App\Classes\Product();
                $products=$product->getAllProductsIds();
                $productModel=fetchProductImages($products);
                generateProduct($productModel,$model::$imageFolder,$thumbs);
            break;
            case 'delete': 
                $product =new \App\Classes\Product();
                $products=$product->getAllProductsIds();
                deleteFolders($products,$model::$imageFolder,$data['name']);
            break;
        }
    }
}

function typeCategoryModel($data,$model,$thumbs){
    if($data['model']){
        switch($data['type']){
            case 'one': 
                $results=DB::query("SELECT id,categories_contents->>'$.nl' as categories_contents  FROM ".$model->getTableName()." where id =%i",$data['id']);
                foreach($results as $key => $result){
                    $results[$key]['categories_contents']=json_decode($result['categories_contents'],true);
                     //$categoryContent=json_decode($results['categories_contents'],true);
                    foreach($results[$key]['categories_contents'] as $category){
                        if($category['image'] && $category['image'] !=''){
                            $results = DB::query("SELECT id,photo FROM " .$model->getTableName() . " where id = %i",$data['id']);
                            $tmp = array();
                            $tmp[]=array('id' => $results[$key]['id'] . '_' . $category['id'], 'photo' => $category['image'] , 'name' => 'type_categories');
                            generate($tmp,'type_categories',$thumbs);
                        }
                      
                    }
                }
            break;
            case 'from': 
                $results = DB::query("SELECT id,categories_contents->>'$.nl' as categories_contents FROM " . $model->getTableName(). "
                where id >=%i and id <= %i order by id",$data['from'],$data['to']);
                foreach($results as $key => $result){
                    if(!($result['categories_contents']))continue;
                    $results[$key]['categories_contents']=json_decode($result['categories_contents'],true);
                    //$categoryContent=json_decode($results['categories_contents'],true);
                    foreach($results[$key]['categories_contents'] as $category){
                        if($category['image'] && $category['image'] !=''){
                            $tmp = array();
                            $tmp[]=array('id' => $results[$key]['id'] . '_' . $category['id'], 'photo' => $category['image'] , 'name' => 'type_categories');
                            generate($tmp,'type_categories',$thumbs);
                        }
                    }
                }
            break;
            case 'all': 
                $results = DB::query("SELECT id,categories_contents->>'$.nl' as categories_contents FROM " . $model->getTableName()." order by id");
                foreach($results as $key => $result){
                    if(!($result['categories_contents']))continue;
                    $results[$key]['categories_contents']=json_decode($result['categories_contents'],true);
                    //$categoryContent=json_decode($results['categories_contents'],true);
                    foreach($results[$key]['categories_contents'] as $category){
                        if($category['image'] && $category['image'] !=''){
                            $tmp = array();
                            $tmp[]=array('id' => $results[$key]['id'] . '_' . $category['id'], 'photo' => $category['image'] , 'name' => 'type_categories');
                            generate($tmp,'type_categories',$thumbs);
                        }
                    }
                }
            break;
            case 'delete': 
                $results = DB::query("SELECT id,categories_contents->>'$.nl' as categories_contents FROM " . $model->getTableName()." order by id");
                foreach($results as $key => $result){
                    if(!($result['categories_contents']))continue;
                    $results[$key]['categories_contents']=json_decode($result['categories_contents'],true);
                    //$categoryContent=json_decode($results['categories_contents'],true);
                    $tmp = array();
                    foreach($results[$key]['categories_contents'] as $category){
                        if($category['image'] && $category['image'] !=''){
                            $tmp[]=array('id' => $results[$key]['id'] . '_' . $category['id']);
                        }
                    }
                    if(count($tmp)>0){
                        deleteFolders($tmp,'type_categories',$data['name']);
                    }
                    unset($tmp);
                }
            break;
        }
    }
}

function allModels($data,$model,$thumbs){
    if($data['model']){
        switch($data['type']){
            case 'one': 
                $results = DB::query("SELECT id,photo FROM " .$model->getTableName() . " where id = %i",$data['id']);
                generate($results,$model::$imageFolder,$thumbs);
            break;
            case 'from': 
                $results = DB::query("SELECT id,photo FROM " . $model->getTableName(). "
                where id >=%i and id <= %i order by id",$data['from'],$data['to']);
                generate($results,$model::$imageFolder,$thumbs);
            break;
            case 'all': 
                $results=$model::All();
                generate($results,$model::$imageFolder,$thumbs);
            break;
            case 'delete': 
                $results=$model::All();
                deleteFolders($results,$model::$imageFolder,$data['name']);
            break;
        }
    }
}

function generate($results,$imageFolder,$thumbs){

        foreach($results as $result){
            $tmp = array('id' => $result['id'], 'photo' => $result['photo'], 'name' => $imageFolder);
            if(!getimagesize(IMAGE_PATH.'/'.$result['photo']))continue;
            if(!GenerateThumbs($tmp, $result['photo'], $thumbs)){
                echo("\033[31m Stoped on this ID ( ". $result['id']." ) and this photo (".$result['photo'].") \033\n");
            }
        }
        echo("\033[32m The thumbs are generated successfully \033\n");
}

function generateProduct($results,$imageFolder,$thumbs){
    foreach($results as $result){
        foreach($result['images'] as $image){
            $tmp = array('id' => $result['id'], 'photo' => $image['url'], 'name' => $imageFolder);
            if(!getimagesize(IMAGE_PATH.'/'.$image['url']))continue;
            if(!GenerateThumbs($tmp, $image['url'], $thumbs)){
                echo("\033[31m Stoped on this ID ( ". $result['id']." ) and this photo (".$image['url'].") \033\n");
             }
        }
     }
     echo("\033[32m  The thumbs are generated successfully !!!! \033\n");
}

function fetchProductImages($products){
    $tmp=array();
    foreach($products as $product){
        $tmp[$product]['id']=$product;
        $tmp[$product]['images'] = DB::query("SELECT url FROM product_meta WHERE product_id=%i and non_image=0 ORDER BY sort_order ASC", $product);
    }
    return $tmp;
}
die();