<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\DealWeek;
use App\Models\Listingmenu;
use DB;
use Session;
use Cache;
trait productListingTrait
{

	public function TitleDecode($title)
	{
       $menu_title=str_replace("-" ," ",(strtolower($title)));
		return $menu_title;		
	}
	public function GetCatTree($CatArray=array('0'=>0))
	{
		//$Categories = Category::where('parent_id','=','0')->where('status','=','1')->with('children')->get();
		$HomeLink = config('const.SITE_URL');
		$Categories = $this->get_all_categories();
		//dd($Categories);
		$SubCatsTree=[];$key=0;
		$ProdCats = [];
		$AllCats = $this->MyCatTree($Categories);
		//$AllCats = array();
		if(isset($AllCats) && !empty($AllCats)){
		foreach($AllCats as $MainCat)
		{ 
		
			
				if(in_array($MainCat->category_id,$CatArray) || $CatArray[0] == 0)
				{
					
					if(empty($MainCat->category_url))  {
						
						if($MainCat->parent_id==0){
							
							//$mainCategoryUrl=config('const.SITE_URL').'/'.title($MainCat->url_name).'.html';
							if(!empty($MainCat->url_name)){
								$mainCategoryUrl=config('const.SITE_URL').'/'.title(strtolower($MainCat->category_name)).'.html';
							}else{
								$mainCategoryUrl=config('const.SITE_URL').'/'.title(strtolower($MainCat->category_name)).'.html';
							}
						}
						else if($MainCat->template_page=="product_list"){
							//$mainCategoryUrl=config('const.SITE_URL').'/'.title($MainCat->url_name)."/cid/".$MainCat->category_id;
							if(!empty($MainCat->url_name)){
								$mainCategoryUrl=config('const.SITE_URL').'/'.title($MainCat->url_name)."/cid/".$MainCat->category_id;
							}else{
								$mainCategoryUrl=config('const.SITE_URL').'/'.title(strtolower($MainCat->category_name))."/cid/".$MainCat->category_id;
							}
						}
					}else{
						$mainCategoryUrl=$MainCat->category_url;
					}
				
				$SubCatsTree[$key][]=['category_id' => $MainCat->category_id, 'category_name' => $MainCat->category_name, 'Level' => 0,'product_link' => $mainCategoryUrl];
				$SubCatBredcrum = ucwords($MainCat->category_name);
				$BredCrum[0]['id'] = 0;
				$BredCrum[0]['title'] = 'Home';
				$BredCrum[0]['link'] = $HomeLink;
				$BredCrum[1]['id'] = $MainCat->category_id;
				$BredCrum[1]['title'] = ucwords($MainCat->category_name);
				//$BredCrum[1]['link'] = $HomeLink.'/'.remove_special_chars(trim($MainCat->category_name)) . '/cid/' . $MainCat->category_id;
				if($MainCat->template_page=="category_list" && $MainCat->parent_id==0){
					$BredCrum[1]['link']= $HomeLink.'/'.remove_special_chars(trim($MainCat->category_name)) .'.html';
				}
				else if($MainCat->template_page=="product_list"){
					$BredCrum[1]['link']= $HomeLink.'/'.remove_special_chars(trim($MainCat->category_name)) . '/cid/' . $MainCat->category_id;
				}
				$ProdCats[$MainCat->category_id] = [
					'slug' => remove_special_chars($MainCat->category_name).'/',
					'category_name' => $MainCat->category_name,
					'bredcrum' => $BredCrum,
					'subcatbredcrum' => $SubCatBredcrum,
					'parent_id' => 0,
					'root_parent_id' => 0,
					'category_url' => $mainCategoryUrl
				];

				if(isset($MainCat->childs) && count($MainCat->childs) > 0 ){
					foreach($MainCat->childs as $SubLevel1){
						if(empty($SubLevel1->category_url))  {
							$subCategoryUrl = config('const.SITE_URL').'/'.title($MainCat->category_name).'/'.title($SubLevel1->category_name)."/cid/".$SubLevel1->category_id;
						}else{
							$subCategoryUrl = $SubLevel1->category_url;
						}
						
						$SubAllCats = isset($SubLevel1->childs)?$SubLevel1->childs:[];
						$SubCatsTree[$key][]=['category_id' => $SubLevel1->category_id, 'category_name' => $SubLevel1->category_name,'hasChild' => ($SubAllCats != null && count($SubAllCats) > 0) ? 'Yes':'No', 'Level' => 1 ,'product_link' => $subCategoryUrl];

						$SubCatBredcrum1 = $SubCatBredcrum.' - '.ucwords($SubLevel1->category_name);
						$BredCrum[2]['id'] = $SubLevel1->category_id;
						$BredCrum[2]['title'] = ucwords($SubLevel1->category_name);
						$BredCrum[2]['link'] = $HomeLink.'/'.remove_special_chars(trim($SubLevel1->category_name)) . '/cid/' . $SubLevel1->category_id;
						$ProdCats[$SubLevel1->category_id] = [
							'slug' => remove_special_chars($MainCat->category_name).'/'.remove_special_chars($SubLevel1->category_name).'/',
							'category_name' => $SubLevel1->category_name,
							'bredcrum' => $BredCrum,
							'subcatbredcrum' => $SubCatBredcrum1,	
							'parent_id' => $SubLevel1->category_id,
							'root_parent_id' => $MainCat->category_id,
							'category_url' => $subCategoryUrl
						];

						$SubCats[]=['category_id' => $SubLevel1->category_id, 'category_name' => $SubLevel1->category_name ,'product_link' => config('const.SITE_URL').'/'.title($MainCat->category_name).'/'.title($SubLevel1->category_name)."/cid/".$SubLevel1->category_id];

						if($SubAllCats){
							foreach($SubAllCats as $SubLevel2){
								if(empty($SubLevel2->category_url))  {
									$subChildCategoryUrl = config('const.SITE_URL').'/'.title($MainCat->category_name).'/'.title($SubLevel1->category_name).'/'.title($SubLevel2->category_name)."/cid/".$SubLevel2->category_id;
								}else{
									$subCategoryUrl = $SubLevel1->category_url;
								}
								$SubCatBredcrum2= $SubCatBredcrum.' - '.ucwords($SubLevel1->category_name).' - '.ucwords($SubLevel2->category_name);
								$BredCrum[3]['id'] = $SubLevel2->category_id;	
								$BredCrum[3]['title'] = ucwords($SubLevel2->category_name);
								$BredCrum[3]['link'] = $HomeLink.remove_special_chars(trim($SubLevel2->category_name)) . '/cid/' . $SubLevel2->category_id;
								$ProdCats[$SubLevel2->category_id] = [
									'slug' => remove_special_chars($SubLevel1->category_name).'/'.remove_special_chars($SubLevel2->category_name).'/',
									'category_name' => $SubLevel2->category_name,
									'bredcrum' => $BredCrum,
									'subcatbredcrum' => $SubCatBredcrum2,
									'parent_id' => $SubLevel2->category_id,
									'root_parent_id' => $MainCat->category_id,
									'category_url' => $subChildCategoryUrl
								];
								$SubCatsTree[$key][]=['category_id' => $SubLevel2->category_id, 'category_name' => $SubLevel2->category_name, 'Level' => 2 ,'product_link' => config('const.SITE_URL').'/'.title($MainCat->category_name).'/'.title($SubLevel1->category_name).'/'.title($SubLevel2->category_name)."/cid/".$SubLevel2->category_id];
								$SubCats[]=['category_id' => $SubLevel2->category_id, 'category_name' => $SubLevel2->category_name ,'product_link' => $subChildCategoryUrl];
								$key++;
							}
						}
						$key++;
					}
				}
				$key++;
			}
			
		}
		}else{
			$ProdCats  = array();
			$CatTree  = array();
		}
		/*echo "<pre>";
		print_r($SubCatsTree);*/
		return ['CatForProd' => $ProdCats, 'CatTree' => $SubCatsTree];

		//return $SubCatsTree;

	}
	public function MyCatTree($Cats)
	{   
		$childs = array();
		foreach($Cats as $item){
			$childs[$item->parent_id][] = $item;
			unset($item);
		}
		foreach($Cats as $item){
			if (isset($childs[$item->category_id])){
				//$item['childs'] = $childs[$item->category_id];
				$item->childs = $childs[$item->category_id];
			}
		}
		return $childs[0];
	}
	
	public function ListingMenu()
	{
		$ListingMenus = Listingmenu::where('display','=','Yes')->orderBy('id')->get();
		$Listing = [];
		if($ListingMenus && $ListingMenus->count() > 0)
		{
			foreach($ListingMenus as $ListingMenu)
				$Listing[$ListingMenu->table_fieldName] = $ListingMenu;
		}
		return $Listing;
	}
	public function UniqueKey($Array, $key, $column,$flag='') {
		$ItemsData = [];
		foreach ($Array as $item) {
			if(isset($item->$column) && $item->$column != ''){
				if($key == 'brand_id')
				{
					
					if(isset($flag) && isset($item->is_popular) && $item->is_popular == 'Yes' && $flag=='ProductListPage')
					{
						
						$ItemsData[ucwords($item->$key)] = ucwords($item->$column);
					}
					else if($flag!='ProductListPage')
					{
						$ItemsData[ucwords($item->$key)] = ucwords($item->$column);
					}
				} else { 
					$ItemsData[ucwords($item->$key)] = ucwords($item->$column);
				}
			}
		}
		$ItemsData = array_unique($ItemsData);
		return $ItemsData;
	}
	public function CountOptions($VariationIDs=[],$DisplayProducts=[],$CatArrVal=[],$Flag='')
	{
		$VariationIDs = array_unique($VariationIDs);
		$VariationProductQry = DB::table('hba_products as po')
							->join('hba_products_category as pc','po.product_id','=','pc.products_id')
							->join('hba_category as c','pc.category_id','=','c.category_id')
						    ->join('hba_brand as b',function($join){
								$join->on('po.brand_id','=','b.brand_id');
						    })
							->select('po.product_id','po.sku','po.size','po.skin_type','po.product_name','po.brand_id','po.is_atomizer',
							    'po.image_name','po.current_stock','po.retail_price','po.gender','po.new_arrival','po.featured','po.clearance','po.best_seller','po.product_type','po.wholesale_price','po.our_price','po.sale_price','po.display_rank','b.brand_name','b.is_popular','pc.category_id','c.parent_id');
							/*->addSelect(['TotalRate' => ProductsReview::select(DB::raw('SUM(star_rate)'))
										->where('approved','=','Yes')->where('star_rate','!=','0')->whereColumn('sku','=','po.sku')
									,'TotalReview' => ProductsReview::select(DB::raw('COUNT(review_id)'))
										->where('approved','=','Yes')->where('star_rate','!=','0')->whereColumn('sku','=','po.sku')]);	*/		
							//->whereIn('po.variation_id',$VariationIDs)
							
		if($Flag == "TOP_SELLERS" || $Flag == "NEW_ARRIVALS")
		{
			if(count($CatArrVal) > 0)
				$VariationProductQry->whereIn('pc.category_id',$CatArrVal);
		}else{	
			$VariationProductQry->whereIn('po.variant',$VariationIDs);		
		}
			$VariationProductQry->where('po.status','=','1')->where('c.status','=','1')->where('b.status','=','1');
		if(Session::get('eusertype') && strtolower(Session::get('eusertype')) == 'wholesaler')
            $VariationProductQry->whereIn('po.product_type',['both','retailer','wholesaler']);
        else
            $VariationProductQry->whereIn('po.product_type',['both','retailer']);					
		
		$VariationProductQry->orderBy('po.current_stock');
		
		$VariationProducts = $VariationProductQry->groupBy('po.product_id')->get()->toArray();
				
		$TotalVariations=[];
		$Variation='';
		$vcount = 0;
		
		$TotalVariations = array_count_values(array_column($VariationProducts, 'variant'));
		$ProdCnt = [];
		$Price = [];
		foreach($VariationProducts as $Product)
		{
			$Product = $this->SetProduct($Product);
			$Price[$Product->variant][] = (float)$Product->product_price;
		}
		$NewProduct = [];
		
		foreach($DisplayProducts as $ProductNew)
		{
			if(isset($TotalVariations[$ProductNew->variant]))
				$ProductNew->size_cnt = $TotalVariations[$ProductNew->variant];
			else
				$ProductNew->size_cnt = 0;
			
			if(isset($Price[$ProductNew->variant]) && count($Price[$ProductNew->variant]) > 0)
			{
				$ProductNew->minPrice = min($Price[$ProductNew->variant]);
				$ProductNew->maxPrice = max($Price[$ProductNew->variant]);
			} else {
				$ProductNew->minPrice = 0;
				$ProductNew->maxPrice = 0;
			}
			$NewProduct[] = $ProductNew;
		}
		
		for($i=0;$i<count($NewProduct);$i++)
		{
			if($NewProduct[$i]->is_atomizer == "Yes" || $NewProduct[$i]->stock == "Out")
			{
				foreach($VariationProducts as $Product)
				{
					if ($Product->products_id == $NewProduct[$i]->products_id)
					continue;
					if ($Product->variant != $NewProduct[$i]->variant)
						continue;

					$Product = $this->SetProduct($Product);

					if ($Product->is_atomizer == "Yes" && $Product->stock == "Out")
						continue;
					if ($Product->stock == "Out" && $Product->is_atomizer == "No")
						continue;

					if ($Product->is_atomizer == "No") 
					{
						if ($Product->stock == "In" && $Product->category_id == $NewProduct[$i]->category_id) 
						{
							$NewProduct[$i] = $this->PrepareProduct($Product,$i);
							if(isset($TotalVariations[$NewProduct[$i]->variant]))
								$NewProduct[$i]->size_cnt = $TotalVariations[$NewProduct[$i]->variant];
							else
								$NewProduct[$i]->size_cnt = 0;
							
							if(isset($Price[$NewProduct[$i]->variant]) && count($Price[$NewProduct[$i]->variant]) > 0)
							{
								$NewProduct[$i]->minPrice = min($Price[$NewProduct[$i]->variant]);
								$NewProduct[$i]->maxPrice = max($Price[$NewProduct[$i]->variant]);
							} else {
								$NewProduct[$i]->minPrice = 0;
								$NewProduct[$i]->maxPrice = 0;
							}
							
							if (count($CatArrVal) > 0) {
								$isAtom1 = 'No';
								for ($j = 0; $j < count($CatArrVal); $j++) {
									if ($CatArrVal[$j] != 68 && $CatArrVal[$j] != 70 &&  $CatArrVal[$j] != 71 &&  $CatArrVal[$j] != 69) {
										$isAtom1 = 'Yes';
									}
								}
								if ($isAtom1 == 'Yes') {
									break;
								}
							} else {
								break;
							}
						} else if ($Product->stock == "In") {
							$NewProduct[$i] = $this->PrepareProduct($Product,$i);
							if(isset($TotalVariations[$NewProduct[$i]->variant]))
								$NewProduct[$i]->size_cnt = $TotalVariations[$NewProduct[$i]->variant];
							else
								$NewProduct[$i]->size_cnt = 0;
							
							if(isset($Price[$NewProduct[$i]->variant]) && count($Price[$NewProduct[$i]->variant]) > 0)
							{
								$NewProduct[$i]->minPrice = min($Price[$NewProduct[$i]->variant]);
								$NewProduct[$i]->maxPrice = max($Price[$NewProduct[$i]->variant]);
							} else {
								$NewProduct[$i]->minPrice = 0;
								$NewProduct[$i]->maxPrice = 0;
							}

						} else {
							if ($NewProduct[$i]->is_atomizer != 'Yes' && $NewProduct[$i]->stock != 'In') {
								$NewProduct[$i] = $this->PrepareProduct($Product,$i);
								if(isset($TotalVariations[$NewProduct[$i]->variant]))
									$NewProduct[$i]->size_cnt = $TotalVariations[$NewProduct[$i]->variant];
								else
									$NewProduct[$i]->size_cnt = 0;
								
								if(isset($Price[$NewProduct[$i]->variant]) && count($Price[$NewProduct[$i]->variant]) > 0)
								{
									$NewProduct[$i]->minPrice = min($Price[$NewProduct[$i]->variant]);
									$NewProduct[$i]->maxPrice = max($Price[$NewProduct[$i]->variant]);
								} else {
									$NewProduct[$i]->minPrice = 0;
									$NewProduct[$i]->maxPrice = 0;
								}
							}
						}
					} else {
						if ($Product->stock == "In" && ($NewProduct[$i]->stock != 'In' || in_array(68, $CatArrVal) || in_array(70, $CatArrVal) || in_array(71, $CatArrVal) || in_array(69, $CatArrVal))) {
							$NewProduct[$i] = $this->PrepareProduct($Product,$i);
							if(isset($TotalVariations[$NewProduct[$i]->variant]))
								$NewProduct[$i]->size_cnt = $TotalVariations[$NewProduct[$i]->variant];
							else
								$NewProduct[$i]->size_cnt = 0;
							
							if(isset($Price[$NewProduct[$i]->variant]) && count($Price[$NewProduct[$i]->variant]) > 0)
							{
								$NewProduct[$i]->minPrice = min($Price[$NewProduct[$i]->variant]);
								$NewProduct[$i]->maxPrice = max($Price[$NewProduct[$i]->variant]);
							} else {
								$NewProduct[$i]->minPrice = 0;
								$NewProduct[$i]->maxPrice = 0;
							}
							$isAtom = 'No';
							for ($j = 0; $j < count($CatArrVal); $j++) {
								if ($CatArrVal[$j] == 68 || $CatArrVal[$j] == 70 ||  $CatArrVal[$j] == 71 ||  $CatArrVal[$j] == 69) {
									$isAtom = 'Yes';
								}
							}
							if ($isAtom == 'Yes')
								break;
						}
					}
				}	
			}
		}
		/*echo "<pre>";
		print_r($DisplayProducts);
		*/
		return $NewProduct;
	}
	public function setPriceRange($variation_id, $Products)
	{
		return $this->GetMinMaxPrice($variation_id, $Products);
	}

	public function GetMinMaxPrice($variant, $Products)
	{
		$Price = [];
		$YouSave = [];	
		foreach ($Products as $ObjProduct) {
			if ($ObjProduct->variant != $variant)
				continue;
			$Product = $ObjProduct;
			$Price[] = $Product->our_price;
			$NewPrice = (int)$Product->retail_price - (int)$Product->our_price;
			if ($NewPrice > 0 )
				$Save = (($Product->retail_price - $Product->our_price) / $Product->retail_price) * 100;
			else
				$Save = 0;
			$YouSave[] = $Save;

		}
		$MinPrice = min($Price);
		$MaxPrice = max($Price);
		$PriceArray = ['MinPrice' => $MinPrice, 'MaxPrice' => $MaxPrice, 'YouSave' => max($YouSave)];
		return $PriceArray;
	}
	public function SetProductURL($ProdID,$ProdName,$CategoryID)
	{
		$ProdLink = config('const.SITE_URL');
		$ProdName = remove_special_chars($ProdName);
		$AllCategoriesInfo = config('CATEGORY_INFO');
		$CatInfo = $AllCategoriesInfo['CatForProd'];
		if(!isset($CatInfo[$CategoryID]))
		{
			$CatId = DB::table('hba_products_category as pc')
				->select('c.category_id')
				->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
	            		->where('pc.products_id', '=', $ProdID)
	            		->where('c.status', '=', '1')
				->orderBy('c.display_position')
				->orderBy('c.category_name')
				->offset(0)->limit(1)->get();

			$CategoryID = $CatId[0]->category_id;

		}
		$CatLink = $CatInfo[$CategoryID]['slug'];
		$ProdLink.=$CatLink.$ProdName.'/pid/'.$ProdID.'/'.$CategoryID;
		return $ProdLink;
	}
	public function SetArray($SizeArray=[],$sizekey)
	{
		$NewSizeArray = [];
		$SizeSortArray = [];
		foreach($SizeArray as $skey => $svalue)
		{
			$ExpSize = explode($sizekey,strtolower($svalue));
			array_push($NewSizeArray,['key' => $svalue, 'val' => trim($ExpSize[0])]);
		}
		
		if(count($NewSizeArray) > 0)
		{
			usort($NewSizeArray, function($a, $b) {
				return $a['val'] > $b['val'];
			});
			foreach($NewSizeArray as $nkey => $nval)
			{
				$SizeSortArray[(string)$nval['key']] = $nval['key'];
			}
		}	
		//dd($NewSizeArray);
		return $SizeSortArray;
	}
	//26-09-2023 start

	public function GetProducts($Flag,$CategoryID,$limit=12,$Filters=[], $type = 'all')
	{ 
		
		$FilterCategories = [];
		$Offset = 0;
		$SortBy = "";
		$CatProdsQry = [];
		$ChildCatArr = [];
		$table_prefix = env('DB_PREFIX', '');
		$BrandInSearch = 0;
		

		
		if(isset($Filters['page']) && $Filters['page'] > 1){
			$Offset = ($Filters['page']-1) * $limit;
		}
		//$Offset = 2;
		//dd($Offset);
		$SortBy = isset($Filters['sortby'])?$Filters['sortby']:'';

		$CatProdsQry = DB::table($table_prefix.'products as po')
		->join($table_prefix.'products_category as pc','po.product_id','=','pc.products_id')
		->join($table_prefix.'category as c','pc.category_id','=','c.category_id')
		->leftJoin($table_prefix.'brand as b','po.brand_id','=','b.brand_id')
		->select('po.product_id','po.on_sale','po.sku','po.size','po.skin_type','po.product_name','po.brand_id','po.is_atomizer',
		'po.image_name','po.current_stock','po.retail_price','po.gender','po.new_arrival','po.featured','po.clearance','po.best_seller','po.product_type','po.wholesale_price','po.our_price','po.sale_price','po.product_group_code','po.variant','po.product_description','po.product_url','po.image_name','po.stock','po.product_url','pc.category_id','c.parent_id','c.category_name','po.display_rank','po.brand_id','b.brand_name','po.extra_images')
		->where('po.status','=','1')
		->where('c.status','=','1');
		// ->whereRaw(('case WHEN po.on_sale=="yes" THEN po.sale_price > 0 ELSE po.our_price > 0 END'));
		//->where(DB::raw('CASE WHEN po.on_sale="Yes" THEN po.sale_price > 0 ELSE po.our_price > 0 END'));

		//->groupby('po.product_group_code');
	
		

		
			

		$FilterStock = '';
		$FilterMinPrice = '';
		$FilterMaxPrice = '';
		$FilterKey = '';
		
		foreach($Filters as $fkey => $Filter)
		{   
			if(is_array($Filter) && count($Filter) > 0)
			{  
				$allcat = array();
				if($fkey == 'categories'){
					foreach ($Filter  as $key =>  $value) {

						array_push($allcat, (int)$value);
						$fetchvalue = $this->getChildCatIdArray($value);
						if (!empty($fetchvalue)) {
							foreach ($fetchvalue as $value1) {
								array_push($allcat, $value1);
							}
						}
					}
					//dd($allcat);
					$CatProdsQry->whereIn('pc.category_id',$allcat);
				}else if($fkey == 'brands'){

					$CatProdsQry->whereIn('po.brand_id',$Filter);
					$BrandInSearch=1;
				}else if($fkey == 'brandcategories'){

					$CatProdsQry->whereIn('pc.category_id',$Filter);
					$BrandInSearch=1;
				}else if($fkey == 'price'){
					$CatProdsQry->where(function($query) use($Filter,$Flag ){
					foreach($Filter as $FilterKey => $FilterValue) {
						$priceArr = array();
						$priceArr = explode('_',$FilterValue);
						//print_r($priceArr);
						if($Flag == 'DealofweekPage'){
							if(count($priceArr) > 1){
								$query->orWhereBetween('dw.deal_price',[$priceArr[0],$priceArr[1]]);
							}else{
								$query->orWhere('dw.deal_price','>',$priceArr[0]);
							}
						}else if($Flag == 'SalePage'){
							//if(count($priceArr) > 1){
								// $query->orWhereRaw(('CASE WHEN po.sale_price > 0  THEN po.sale_price >= '.$priceArr[0].' ELSE po.our_price >= '.$priceArr[0].' END'));
								// $query->orWhereRaw(('CASE WHEN po.sale_price > 0  THEN po.sale_price <= '.$priceArr[1].' ELSE po.our_price <= '.$priceArr[1].' END'));
								if(count($priceArr) > 1){
									// $query->where(function($queryPrice) use($priceArr){
									// 	$queryPrice->orWhere(('CASE WHEN po.sale_price > 0  THEN po.sale_price >= '.$priceArr[0].' ELSE po.our_price >= '.$priceArr[0].' END'));
									// 	$queryPrice->orWhere(('CASE WHEN po.sale_price > 0  THEN po.sale_price <= '.$priceArr[1].' ELSE po.our_price <= '.$priceArr[1].' END'));
									// });
									$query->orWhereBetween('po.sale_price',[$priceArr[0],$priceArr[1]]);

								
							}else{
								$query->orWhere('po.sale_price','>',$priceArr[0]);
								//$query->whereRaw(('CASE WHEN po.sale_price > 0  THEN po.sale_price > '.$priceArr[0].' ELSE po.our_price > '.$priceArr[0].' END'));
							}
						}else{
							if(count($priceArr) > 1){
								$query->orWhereBetween('po.our_price',[$priceArr[0],$priceArr[1]]);
							}else{
								$query->orWhere('po.our_price','>',$priceArr[0]);
							}
						}
					}
				});
				}else {
					$CatProdsQry->whereIn('po.'.$fkey,$Filter);
				}
			}
		}
		//dd($CatProdsQry);
		if($Flag == 'DealofweekPage'){
			$deal_product_sku = '';
			$currentDate = getDateTimeByTimezone('Y-m-d');
			//$CatProdsQry->orderBy('po.display_rank');
			// $homedealCatProds = DB::table($table_prefix.'dealofweek as dw');
			
			// $homedealCatProds->select('dw.product_sku');
			// $homedealCatProds->where('dw.deal_type','=','Weekly');
			// $homedealCatProds->where('dw.status','=','1');
			// $homedealCatProds->Where('dw.display_on_home','Yes');
			// $homedealCatProds->where('dw.start_date','<=',$currentDate)->where('dw.end_date','>=',$currentDate);
			// $homedealCatProdsWithoutLimit = $homedealCatProds->get()->pluck('product_sku')->toarray();
			
			// $deal_sku_array = $homedealCatProdsWithoutLimit;
			
			// $CatProdsQry->where(function($query) use($deal_sku_array) {
			// 	//$query->orWhere('po.display_deal_of_week','Yes')->whereIn('po.sku',$deal_sku_array,'or');
			// 	$query->whereIn('po.sku',$deal_sku_array,'or');
			// });
			$CatProdsQry->join($table_prefix.'dealofweek as dw','dw.product_sku','=','po.sku');
			$CatProdsQry->addSelect(DB::raw("dw.description as deal_description,dw.deal_price as our_price")); 
			$CatProdsQry->where('dw.deal_type','=','Weekly');
			$CatProdsQry->where('dw.status','=','1');
			$CatProdsQry->Where('dw.display_on_home','Yes');
			$CatProdsQry->where('dw.start_date','<=',$currentDate)->where('dw.end_date','>=',$currentDate);
			
		}
		else if($Flag == 'NewArrivalPage'){
			 $CatProdsQry->where('new_arrival','=','Yes');
		}else if($Flag == 'FeaturedPage'){
			$CatProdsQry->where('featured','=','Yes');
	   	}
		else if($Flag == 'SeosonalSpecialsPage'){
			$CatProdsQry->where('seasonal_specials','=','Yes');
		}else if($Flag == 'BrandsList'){
			$CatProdsQry->orderBy('po.display_rank', 'ASC');
			//$CatProdsQry->orderBy('po.brand_id');
		}
		else if($Flag == 'SalePage'){
			//$CatProdsQry->addSelect(DB::raw("po.our_price as retail_price,po.sale_price as our_price")); 
			$CatProdsQry->where('po.on_sale', '=', 'Yes');
		}
		else if($Flag == 'BrandsList'){
			$CatProdsQry->orderBy('po.brand_id');
		}
		else if($Flag == 'CategoryPage'){
			$CatProdsQry->where('po.best_seller', '=', 'Yes');
			$CatProdsQry->orderBy('po.display_rank');
		}
		else if($Flag == 'BestsellerPage'){
			 $CatProdsQry->where('po.best_seller', '=', 'Yes');
		}
		if (isset($Filters['sortby']) && $Filters['sortby'] != '') {
            $sort_by = $Filters['sortby'];
            if ($sort_by == 'PLTH') {
                if($Flag == 'DealofweekPage'){
					$CatProdsQry->orderBy('dw.deal_price', 'ASC');
				}else if($Flag == 'SalePage'){
					//$CatProdsQry->orderBy('dw.sale_price', 'ASC');
					$CatProdsQry->orderByRaw('(CASE WHEN po.sale_price > 0 THEN po.sale_price ELSE po.our_price END ) ASC');
				}else{
					//$CatProdsQry->orderByRaw('(CASE WHEN po.sale_price > 0 THEN po.sale_price ELSE po.our_price END ) ASC');
					$CatProdsQry->orderBy('po.our_price', 'ASC');
				}
            } elseif ($sort_by == 'PHTL') {
                
				if($Flag == 'DealofweekPage'){
					$CatProdsQry->orderBy('dw.deal_price', 'DESC');
				}else if($Flag == 'SalePage'){
					//$CatProdsQry->orderBy('dw.sale_price', 'ASC');
					$CatProdsQry->orderByRaw('(CASE WHEN po.sale_price > 0 THEN po.sale_price ELSE po.our_price END ) DESC');
				}else{
					//$CatProdsQry->orderByRaw('(CASE WHEN po.sale_price > 0 THEN po.sale_price ELSE po.our_price END ) DESC');
					$CatProdsQry->orderBy('po.our_price', 'DESC');
				}
            }elseif ($sort_by == 'AZ') {
                $CatProdsQry->orderBy('po.product_name', 'ASC');
            } elseif ($sort_by == 'ZA') {
                $CatProdsQry->orderBy('po.product_name', 'DESC');
            }
        } else {
			if($Flag == 'DealofweekPage'){
				$CatProdsQry->orderBy('dw.display_rank', 'ASC');
			}else{
				//$CatProdsQry->orderByRaw('(CASE WHEN po.sale_price > 0 THEN po.sale_price ELSE po.our_price END ) ASC');
				$CatProdsQry->orderBy('po.display_rank', 'ASC');
			}
        }
		//$tempQuery = clone $CatProdsQry;
		//dd($CatProdsQry->all());
			
		$CatProdsWithoutLimit = $CatProdsQry->get();
		// $CatProdsWithoutLimit = $CatProdsWithoutLimit->filter(function ($CatProdsQry) {
		// 	if ($CatProdsQry->on_sale == 'Yes') {
		// 		return $CatProdsQry->sale_price > 0 ? $CatProdsQry : false;
		// 	}else{
		// 		return  $CatProdsQry->our_price > 0 ? $CatProdsQry : false;
		// 	}
		// });
		//dd($CatProdsWithoutLimit);
		$CatProdsWithoutLimit = $CatProdsWithoutLimit->groupBy('product_group_code');
		$CatProdsWithoutLimit = $CatProdsWithoutLimit->map(function ($item, $key) {
			return collect($item)->first();
		});
	
		//dd($CatProdsWithoutLimit);

		//dd($CatProdsWithoutLimit);
		//$CatProdsWithoutLimit =  array();
		$SelCatDetails = $this->GetCatTree();
		$CategoryList = $this->UniqueKey($CatProdsWithoutLimit,'category_id','category_name');
		$categoryIdArr = array();
		if(isset($CategoryList) && !empty($CategoryList)){
			$categoryIds = array_flip($CategoryList);
			foreach($categoryIds as $categoryIdsKey => $category_id){
				if(isset($SelCatDetails['CatForProd'][$category_id]) && !empty($SelCatDetails['CatForProd'][$category_id])){
					$CatInfo = $SelCatDetails['CatForProd'][$category_id];
					$ParentID = ($CatInfo['root_parent_id'] != '0'?$CatInfo['root_parent_id']:$category_id);
					$categoryIdArr[] = $ParentID;
				}
			}
			if(!empty($categoryIdArr) && !empty($categoryIdArr)){
			$CatArray = $this->GetCatTree(array_values($categoryIdArr));
			$Categories = $CatArray['CatTree'];
			}else{
				$Categories = array();	
			}
			
		}else{
			$Categories = array();
		}
		// $TotalProducts = $TotalProds;
		
		$AllFilters = $this->GetFilters($CatProdsWithoutLimit,$Filters,$Flag);

		$ArrayFilters = ['sortby' => $SortBy, 'offset' => $Offset, 'limit' => $limit];
		$SKUs = '' ;
		$CatProducts = [];
		$TotalProds = 0;
		$VariationIDs=[];
		$ProdIds=[];
		$DealData =[];
		$MyCatProdsWithoutLimit1 = $CatProdsWithoutLimit->unique('product_id');
		$countAllRecords = count($MyCatProdsWithoutLimit1);
		//$MyCatProdsWithoutLimit = $CatProdsQry->offset($Offset)->take($limit)->get();

		
		if($type == 'on_load'){
			$MyCatProdsWithoutLimit = $MyCatProdsWithoutLimit1->take($limit*$Filters['page'])->toarray();
		}else{
			$MyCatProdsWithoutLimit = $MyCatProdsWithoutLimit1->slice($Offset, $limit)->toarray();
		}
		//dd($MyCatProdsWithoutLimit);
		$MyCatProdsSplitArr = array_chunk($MyCatProdsWithoutLimit, 100);
		//$CatProdsQry->chunk(15000, function($MyCatProdsWithoutLimit)use(&$CatProducts,&$VariationIDs,&$ProdIds,&$TotalProds,$CategoryID,$DealData,$FilterStock,$FilterMinPrice,$FilterMaxPrice,$BrandInSearch)
		$allDealOFWeekArr = get_deal_of_week_by_sku();
		foreach($MyCatProdsSplitArr as $key => $CatProdArr)
		 {
			//dd(count($MyCatProdsWithoutLimit));
		
			foreach($CatProdArr as $key => $CatProd)
			{
				if($Flag != 'DealofweekPage'){
					if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
					
						if(isset($allDealOFWeekArr[$CatProd->sku]) && !empty($allDealOFWeekArr[$CatProd->sku])){
							$CatProd->our_price = $allDealOFWeekArr[$CatProd->sku]->deal_price;
							$CatProd->sale_price = $allDealOFWeekArr[$CatProd->sku]->deal_price;
							$CatProd->deal_description = $allDealOFWeekArr[$CatProd->sku]->description;
						}	
					}
				}
				//echo $key;
				$CatProd = json_decode(json_encode($this->get_whishlist(json_decode(json_encode($CatProd),true))));
				$CatProd->image_url = Get_Product_Image_URL($CatProd->image_name,'THUMB');
				
				// Get first extra image of product for show on hover of product in product list page start
				$CatProd->first_extra_image = "";
				if (trim($CatProd->extra_images) != '') {
					$extra_images = explode('#', $CatProd->extra_images);
					if (count($extra_images) > 0) {
						$CatProd->first_extra_image = Get_Product_Image_URL($extra_images[0], 'THUMB');
					}
				}
				// Get first extra image of product for show on hover of product in product list page end

				if(empty($CatProd->product_url)){
					$CatProd->product_url = Get_Product_URL($CatProd->product_id,$CatProd->product_name,$CatProd->category_id,$CatProd->category_name,$CatProd->sku);
				}
				$CatProd->price_arr = $this->Get_Price_Val(json_decode(json_encode($CatProd),true));
				$CatProducts[] = $CatProd;
			}
			
		 };
		
		
		
		$ProductsDetails = ['Products' => $CatProducts,'TotalProducts' => $countAllRecords, 'LeftFilters' => $AllFilters, 'Categories' => $Categories];
		
		return $ProductsDetails;
		//dd($CatProdsWithoutLimit);
	}
	/*public function GetFilters($Products,$SetFilters=[],$Flag='')
	{
		$Filters=[];
		$f=0;
		
		$productTypeList = $this->UniqueKey($Products,'product_type','product_type',$Flag);

		asort($productTypeList,SORT_STRING);
		
		if(count($productTypeList) > 0 ){
			$Filters[$f]['ProductType']['Attr'] = ['title' => 'Type', 'id' => 'product_type', 'filterval' => 'key'];
			$Filters[$f]['ProductType']['Data'] = $productTypeList;
			$Filters[$f]['ProductType']['Selected'] = isset($SetFilters['product_type'])?$SetFilters['product_type']:[];
			$Filters[$f]['ProductType']['Order'] = $f;
			$f++;
		}

		$BrandList = $this->UniqueKey($Products,'brand_id','brand_name',$Flag);

		asort($BrandList,SORT_STRING);
		
		if(count($BrandList) > 0 ){
			$Filters[$f]['Brands']['Attr'] = ['title' => 'Brands', 'id' => 'brands', 'filterval' => 'key'];
			$Filters[$f]['Brands']['Data'] = $BrandList;
			$Filters[$f]['Brands']['Selected'] = isset($SetFilters['brands'])?$SetFilters['brands']:[];
			$Filters[$f]['Brands']['Order'] = $f;
			$f++;
		}
		
		$GenderList = $this->UniqueKey($Products,'gender','gender');
		asort($GenderList,SORT_STRING);
		
		if(count($GenderList) > 0 ){
			$Filters[$f]['Gender']['Attr'] = ['title' => 'Gender', 'id' => 'gender', 'filterval' => 'key'];
			$Filters[$f]['Gender']['Data'] = $GenderList;
			$Filters[$f]['Gender']['Selected'] = isset($SetFilters['gender'])?$SetFilters['gender']:[];
			$Filters[$f]['Gender']['Order'] = $f;
			$f++;
		}
		
		
		$SizeList = $this->UniqueKey($Products,'size','size');
		
		asort($SizeList,SORT_NUMERIC);
		if(count($SizeList) > 0 ){
			$Filters[$f]['Size']['Attr'] = ['title' => 'Size', 'id' => 'size', 'filterval' => 'key'];
			$Filters[$f]['Size']['Data'] = $SizeList;
			$Filters[$f]['Size']['Selected'] = isset($SetFilters['size'])?$SetFilters['size']:[];
			$Filters[$f]['Size']['Order'] = $f;
			$f++;
		}
	
		return $Filters;
	}*/
	public function GetFilters($Products,$SetFilters=[],$Flag='')
	{
		
		$Filters=[];
		$f=0;
		
		$productTypeList = $this->UniqueKey($Products,'product_type','product_type',$Flag);

		asort($productTypeList,SORT_STRING);
		
		if(count($productTypeList) > 0 ){
			$Filters[$f]['ProductType']['Attr'] = ['title' => 'Type', 'id' => 'product_type', 'filterval' => 'key'];
			$Filters[$f]['ProductType']['Data'] = $productTypeList;
			$Filters[$f]['ProductType']['Selected'] = isset($SetFilters['product_type'])?$SetFilters['product_type']:[];
			$Filters[$f]['ProductType']['Order'] = $f;
			$f++;
		}else{
			$Filters[$f]['ProductType']['Attr'] = ['title' => 'Type', 'id' => 'product_type', 'filterval' => 'key'];
			$Filters[$f]['ProductType']['Data'] = $productTypeList;
			$Filters[$f]['ProductType']['Selected'] = isset($SetFilters['product_type'])?$SetFilters['product_type']:[];
			$Filters[$f]['ProductType']['Order'] = $f;
			$f++;
		}

		$BrandList = $this->UniqueKey($Products,'brand_id','brand_name',$Flag);

		asort($BrandList,SORT_STRING);
		
		if(count($BrandList) > 0 ){
			$Filters[$f]['Brands']['Attr'] = ['title' => 'Brands', 'id' => 'brands', 'filterval' => 'key'];
			$Filters[$f]['Brands']['Data'] = $BrandList;
			$Filters[$f]['Brands']['Selected'] = isset($SetFilters['brands'])?$SetFilters['brands']:[];
			$Filters[$f]['Brands']['Order'] = $f;
			$f++;
		}else{
			$Filters[$f]['Brands']['Attr'] = ['title' => 'Brands', 'id' => 'brands', 'filterval' => 'key'];
			$Filters[$f]['Brands']['Data'] = $BrandList;
			$Filters[$f]['Brands']['Selected'] = isset($SetFilters['brands'])?$SetFilters['brands']:[];
			$Filters[$f]['Brands']['Order'] = $f;
			$f++;
		}
		
		$GenderList = $this->UniqueKey($Products,'gender','gender');
		asort($GenderList,SORT_STRING);
		
		if(count($GenderList) > 0 ){
			$Filters[$f]['Gender']['Attr'] = ['title' => 'Gender', 'id' => 'gender', 'filterval' => 'key'];
			$Filters[$f]['Gender']['Data'] = $GenderList;
			$Filters[$f]['Gender']['Selected'] = isset($SetFilters['gender'])?$SetFilters['gender']:[];
			$Filters[$f]['Gender']['Order'] = $f;
			$f++;
		}else{
			$Filters[$f]['Gender']['Attr'] = ['title' => 'Gender', 'id' => 'gender', 'filterval' => 'key'];
			$Filters[$f]['Gender']['Data'] = $GenderList;
			$Filters[$f]['Gender']['Selected'] = [];
			$Filters[$f]['Gender']['Order'] = $f;
			$f++;	
		}
		
		
		$SizeList = $this->UniqueKey($Products,'size','size');
		
		asort($SizeList,SORT_NUMERIC);

		if(count($SizeList) > 0 ){
			$Filters[$f]['Size']['Attr'] = ['title' => 'Size', 'id' => 'size', 'filterval' => 'key'];
			$Filters[$f]['Size']['Data'] = $SizeList;
			$Filters[$f]['Size']['Selected'] = isset($SetFilters['size'])?$SetFilters['size']:[];
			$Filters[$f]['Size']['Order'] = $f;
			$f++;
		}else{
			$Filters[$f]['Size']['Attr'] = ['title' => 'Size', 'id' => 'size', 'filterval' => 'key'];
			$Filters[$f]['Size']['Data'] = $SizeList;
			$Filters[$f]['Size']['Selected'] = [];
			$Filters[$f]['Size']['Order'] = $f;
			$f++;
		}
		
		if(isset($SetFilters['sortby']) && !empty($SetFilters['sortby'])){
			$Filters[$f]['sortby']['Attr'] = ['title' => '', 'id' => '', 'filterval' => ''];
			$Filters[$f]['sortby']['Data'] = [];
			$Filters[$f]['sortby']['Selected'] = isset($SetFilters['sortby'])?$SetFilters['sortby']:[];
			$Filters[$f]['sortby']['Order'] = $f;
			$f++;
		}else{
			$Filters[$f]['sortby']['Attr'] = ['title' => '', 'id' => '', 'filterval' => ''];
			$Filters[$f]['sortby']['Data'] = [];
			$Filters[$f]['sortby']['Selected'] = [];
			$Filters[$f]['sortby']['Order'] = $f;
			$f++;
		}
		if(isset($SetFilters['price']) && !empty($SetFilters['price'])){
			$Filters[$f]['price']['Attr'] = ['title' => '', 'id' => '', 'filterval' => ''];
			$Filters[$f]['price']['Data'] = [];
			$Filters[$f]['price']['Selected'] = isset($SetFilters['price'])?$SetFilters['price']:[];
			$Filters[$f]['price']['Order'] = $f;
			$f++;
		}else{
			$Filters[$f]['price']['Attr'] = ['title' => '', 'id' => '', 'filterval' => ''];
			$Filters[$f]['price']['Data'] = [];
			$Filters[$f]['price']['Selected'] = [];
			$Filters[$f]['price']['Order'] = $f;
			$f++;
		}
	
		return $Filters;
	}

	public function SetFilters($Params)
	{   

		$ExpFilters = explode("/",$Params->filters);
		if(isset($Params->brand_id) && $Params->brand_id != '')
			$ExpFilters[]='brid-'.$Params->brand_id;
		if(isset($Params->product_type) && $Params->product_type != '')
			$ExpFilters[]='product_type-'.$Params->product_type;
		if(isset($Params->category_id) && $Params->category_id != '')
			$ExpFilters[]='cid-'.$Params->category_id;
		
		$AllFilters = [];
		$ParamString = ['cid' => 'categories', 'brid' => 'brands', 'size' => 'product_type','product_type' => 'size','gender' => 'gender','price' => 'price'];		
		
		foreach($ExpFilters as $AllParam)
		{
			$ExpParam = explode("-",$AllParam);
			if(count($ExpParam)>0 && array_key_exists($ExpParam[0],$ParamString))
			{
				$Key = $ParamString[$ExpParam[0]];
				$AllFilters[$Key] = explode(',',$ExpParam[1]);
			} else if(count($ExpParam)>0 && $ExpParam[0] == 'key'){	
				$AllFilters['key'] = $ExpParam[1];
			} else if(count($ExpParam)>0 && $ExpParam[0] == 'price'){
				$AllFilters['minprice'] = $ExpParam[1];
				$AllFilters['maxprice'] = $ExpParam[2];					
			}					
		}

		return $AllFilters;
	}
	public function Bredcrum($RequestParams,$Flag='',$NoneCategory='')
	{
		$HomeLink = config('const.SITE_URL');
		$i=0;
		$ExpMyCat = [];
		$Bredcrum=[];
		if($RequestParams->category_id && $RequestParams->category_id != '')
		{
			$ExpMyCat = explode(',',$RequestParams->category_id);
		}
		if($RequestParams->category_id && $RequestParams->category_id != '' && count($ExpMyCat) == 1)
		{			
			$CatDetails = $this->GetCatTree();
			if(isset($CatDetails['CatForProd'][$RequestParams->category_id]) && !empty($CatDetails['CatForProd'][$RequestParams->category_id])){
			$BredcrumInfo = $CatDetails['CatForProd'][$RequestParams->category_id]['bredcrum'];
			foreach($BredcrumInfo as $Binfo)
			{
				$Bredcrum[]=$Binfo;
				if($Binfo['id'] == $RequestParams->category_id)
					break;
			}	
		}	
		} else {
			$Bredcrum[$i]['title'] = 'Home';
			$Bredcrum[$i]['link'] = $HomeLink;
		}
		
		if($Flag == 'NewArrivalPage'){
			$i=count($Bredcrum);
			$Title = 'New Arrival';
			$BLink = '/new-arrival.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'SalePage'){
			$i=count($Bredcrum);
			$Title = 'Sale';
			$BLink = '/sale.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'BestsellerPage'){
			$i=count($Bredcrum);
			$Title = 'Best seller';
			$BLink = '/best-seller.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'CatNewArrivals'){
			$i=count($Bredcrum);
			$Title = 'New Arrival';
			$BLink = '/New Arrival.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'CatFeaturedItems'){
			$i=count($Bredcrum);
			$Title = 'Featured Items';
			$BLink = '/featureditems.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'SeosonalSpecialsPage'){
			$i=count($Bredcrum);
			$Title = 'Seasonal Specials';
			$BLink = '/season-special.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'DealofweekPage'){
			$i=count($Bredcrum);
			$Title = 'Deal OF Week';
			$BLink = '/deals-of-weeks.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'BrandsList'){
			if($RequestParams->category_id && $RequestParams->category_id != ''){
				$brand_category_name = title($Bredcrum[1]['title']);
				$Bredcrum = array_splice($Bredcrum, 0, 1);

			}else{
				$brand_category_name = 'all';
			}

			$j=count($Bredcrum);
			$BLink = '/brand-'.$brand_category_name.'.html';
			$Bredcrum[$j]['title'] = ucfirst(strtolower('Brand'));
			$Bredcrum[$j]['link'] = $HomeLink.$BLink;
			$i=count($Bredcrum);
			$Title = $NoneCategory;
			$BLink = '';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
			
		}else if($Flag == 'BrandsPage'){
			
			if($RequestParams->category_id && $RequestParams->category_id != '' && $RequestParams->category_id != -1){
				$brand_category_name = title($Bredcrum[1]['title']);
				$Bredcrum = array_splice($Bredcrum, 0, 1);

			}else{
				$brand_category_name = 'all';
			}

			$j=count($Bredcrum);
			$BLink = '/brand-'.$brand_category_name.'.html';
			$Bredcrum[$j]['title'] = ucfirst(strtolower('Brand'));
			$Bredcrum[$j]['link'] = $HomeLink.$BLink;
			
			
		}
		$BredLink = '';
		if(isset($Bredcrum) && !empty($Bredcrum)){
		foreach($Bredcrum as $key => $BHead)
		{
			if((count($Bredcrum)-1) == $key )
			{
				$BredLink.="<span class='active'>".ucfirst(strtolower($BHead['title']))."</span>";
			} else {
				$BredLink.="<a href='".$BHead['link']."' title='".$BHead['title']."'>".ucfirst(strtolower($BHead['title']))."<svg class='svg_barrow' width='272px' height='74px' aria-hidden='true' role='img'><use href='#svg_barrow' xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='#svg_barrow'></use></svg></a>";
			}
		}
		$BredData = ['BredLink' => $BredLink, 'PageTitle' => ucfirst(strtolower($Bredcrum[count($Bredcrum)-1]['title']))];
	}else{
			$BredData = ['BredLink' => '', 'PageTitle' => ''];
		}
		return $BredData;
	}

	public function BredcrumObj($RequestParams,$Flag='',$NoneCategory='')
	{
		$HomeLink = config('const.SITE_URL');
		$i=0;
		$ExpMyCat = [];
		$Bredcrum=[];
		if($RequestParams->category_id && $RequestParams->category_id != '')
		{
			$ExpMyCat = explode(',',$RequestParams->category_id);
		}
		if($RequestParams->category_id && $RequestParams->category_id != '' && count($ExpMyCat) == 1)
		{			
			$CatDetails = $this->GetCatTree();
			if(isset($CatDetails['CatForProd'][$RequestParams->category_id]) && !empty($CatDetails['CatForProd'][$RequestParams->category_id])){
			$BredcrumInfo = $CatDetails['CatForProd'][$RequestParams->category_id]['bredcrum'];
			foreach($BredcrumInfo as $Binfo)
			{
				$Bredcrum[]=$Binfo;
				if($Binfo['id'] == $RequestParams->category_id)
					break;
			}	
		}	
		} else {
			$Bredcrum[$i]['title'] = 'Home';
			$Bredcrum[$i]['link'] = $HomeLink;
		}
		
		if($Flag == 'NewArrivalPage'){
			$i=count($Bredcrum);
			$Title = 'New Arrival';
			$BLink = '/new-arrival.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'SalePage'){
			$i=count($Bredcrum);
			$Title = 'Sale';
			$BLink = '/sale.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'BestsellerPage'){
			$i=count($Bredcrum);
			$Title = 'Best seller';
			$BLink = '/best-seller.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'CatNewArrivals'){
			$i=count($Bredcrum);
			$Title = 'New Arrival';
			$BLink = '/New Arrival.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'CatFeaturedItems'){
			$i=count($Bredcrum);
			$Title = 'Featured Items';
			$BLink = '/featureditems.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'SeosonalSpecialsPage'){
			$i=count($Bredcrum);
			$Title = 'Seasonal Specials';
			$BLink = '/season-special.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
		}else if($Flag == 'DealofweekPage'){
			$i=count($Bredcrum);
			$Title = 'Deal OF Week';
			$BLink = '/deals-of-weeks.html';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
		}else if($Flag == 'BrandsList'){
			if($RequestParams->category_id && $RequestParams->category_id != '' && $RequestParams->category_id != -1){
				$brand_category_name = title($Bredcrum[1]['title']);
				$Bredcrum = array_splice($Bredcrum, 0, 1);

			}else{
				$brand_category_name = 'all';
			}
			$j=count($Bredcrum);
			$BLink = '/brand-'.$brand_category_name.'.html';
			$Bredcrum[$j]['title'] = ucfirst(strtolower('Brand'));
			$Bredcrum[$j]['link'] = $HomeLink.$BLink;
			$i=count($Bredcrum);
			$Title = $NoneCategory;
			$BLink = '';
			$Bredcrum[$i]['title'] = ucfirst(strtolower($Title));
			$Bredcrum[$i]['link'] = $HomeLink.$BLink;
			
		}else if($Flag == 'BrandsPage'){
		
			if($RequestParams->category_id && $RequestParams->category_id != '' && $RequestParams->category_id != -1){
				$brand_category_name = title($Bredcrum[1]['title']);
				$Bredcrum = array_splice($Bredcrum, 0, 1);

			}else{
				$brand_category_name = 'all';
			}
			$j=count($Bredcrum);
			$BLink = '/brand-'.$brand_category_name.'.html';
			$Bredcrum[$j]['title'] = ucfirst(strtolower('Brand'));
			$Bredcrum[$j]['link'] = $HomeLink.$BLink;
			
		
			
		}
		$BredLink = '';
		if(isset($Bredcrum) && !empty($Bredcrum)){
			$BredData = $Bredcrum;
		}else{
			$BredData = '';
		}
		return $BredData;
	}
		
	
}
?>