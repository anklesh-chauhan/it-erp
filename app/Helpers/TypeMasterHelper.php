<?php

namespace App\Helpers;

use App\Models\TypeMaster;

class TypeMasterHelper
{
    public static function leaf(string $typeableClass, string $parentName): TypeMaster
    {
        $parent = TypeMaster::where('name', $parentName)
            ->where('typeable_type', $typeableClass)
            ->whereNull('parent_id')
            ->firstOrFail();

        return TypeMaster::where('parent_id', $parent->id)
            ->inRandomOrder()
            ->firstOrFail();
    }

    public static function randomLeaf(string $typeableClass): TypeMaster
    {
        return TypeMaster::where('typeable_type', $typeableClass)
            ->whereNotNull('parent_id')
            ->inRandomOrder()
            ->firstOrFail();
    }
}
