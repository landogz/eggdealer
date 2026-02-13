<?php

namespace App\Services\Inventory;

use App\Models\Inventory;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Adjust inventory pieces for a given egg size and recompute trays.
     *
     * @param  int  $eggSizeId
     * @param  int  $deltaPieces  Positive to add stock, negative to subtract.
     * @return \App\Models\Inventory
     */
    public static function adjust(int $eggSizeId, int $deltaPieces): Inventory
    {
        return DB::transaction(function () use ($eggSizeId, $deltaPieces) {
            $inventory = Inventory::firstOrCreate(
                ['egg_size_id' => $eggSizeId],
                [
                    'current_stock_pieces' => 0,
                    'current_stock_trays' => 0,
                    'minimum_stock_alert' => 0,
                ]
            );

            $newPieces = max(0, (int) $inventory->current_stock_pieces + $deltaPieces);

            $traySize = (int) (Setting::query()->value('default_tray_size') ?: 30);
            $traySize = $traySize > 0 ? $traySize : 30;

            $inventory->current_stock_pieces = $newPieces;
            $inventory->current_stock_trays = intdiv($newPieces, $traySize);
            $inventory->last_updated = now();
            $inventory->save();

            return $inventory;
        });
    }
}

