<?php
namespace App\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Support\Facades\Auth;

class AnyPermissionFilter implements FilterInterface
{
    public function transform($item)
    {
        if (isset($item['can'])) {
            // ## Determine if 'can' is an array or a string for flexibility between 'permission1 | permission2' or ['permission1','permission2'] form
            $permissions = is_array($item['can']) ? array_map('trim', $item['can']) : array_map('trim', explode('|', $item['can']));
            if (! Auth::user()->canany($permissions)) {
                $item['restricted'] = true;
            }
        }

        return $item;
    }
}