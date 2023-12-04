<?php

namespace App\Http\Middleware;

use App\Models\ShopOwner;
use Illuminate\Support\Facades\Auth;

/**
 * @method static where(string $string, string $string1, mixed $shop_id)
 * @method static with(string $string)
 */
class ShowOwner
{
    public function getShopOwnerShopIds(): array
    {
        $user = Auth::guard('sanctum')->user();
        $shopOwner = ShopOwner::with('shop')->where('user_id', '=', $user->__get('id'))->get();

        return $shopOwner->pluck('shop.id')->toArray();
    }
}
