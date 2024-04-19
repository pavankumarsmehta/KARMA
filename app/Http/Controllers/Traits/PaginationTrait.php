<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Traits\generalTrait;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait PaginationTrait
{
	public function paginate($items, $perPage = 8, $page = null)
	{
		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
		$total = count($items);
		$currentpage = $page;
		$offset = ($currentpage * $perPage) - $perPage;
		$itemstoshow = array_slice($items, $offset, $perPage);
		return new LengthAwarePaginator($itemstoshow, $total, $perPage);
	}
}
