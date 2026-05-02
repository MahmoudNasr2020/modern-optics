<?php

namespace App\Services;

use App\Branch;
use App\BranchStock;
use App\Product;
use App\glassLense;
use App\StockMovement;
use Illuminate\Support\Facades\DB;

/**
 * ====================================================
 * STOCK MANAGEMENT SERVICE
 * Handles both Products and Lenses
 * ====================================================
 */
class StockService
{
    /**
     * Get stock for any item (product or lens)
     */
    public static function getStock($branchId, $type, $itemId)
    {
        $stockableType = self::getStockableType($type);

        return BranchStock::where('branch_id', $branchId)
            ->where('stockable_type', $stockableType)
            ->where('stockable_id', $itemId)
            ->first();
    }

    /**
     * Check if item has sufficient stock
     */
    public static function hasStock($branchId, $type, $itemId, $quantity = 1)
    {
        $stock = self::getStock($branchId, $type, $itemId);
        return $stock && $stock->available_quantity >= $quantity;
    }

    /**
     * Return stock to existing branch record ONLY
     * Will NOT create new stock
     **/
    public static function returnStock($branchId, $type, $itemId, $quantity, $userId, $notes = null)
    {
        DB::beginTransaction();
        try {
            $stockableType = self::getStockableType($type);

            // ✅ Get existing stock record ONLY (don't create if not exists)
            $stock = BranchStock::where('branch_id', $branchId)
                ->where('stockable_type', $stockableType)
                ->where('stockable_id', $itemId)
                ->first();

            // ✅ Check if stock exists
            if (!$stock) {
                throw new \Exception("Stock record not found for {$type} {$itemId} in branch {$branchId}. Cannot return stock that was never sold from this branch.");
            }

            $balanceBefore = $stock->quantity;

            // ✅ Add quantity to existing stock
            $stock->addQuantity($quantity, null);

            // ✅ Create movement record
            StockMovement::create([
                'branch_id' => $branchId,
                'product_id' => $type === 'product' ? $itemId : null,
                'stockable_type' => $stockableType,
                'stockable_id' => $itemId,
                'type' => 'in',
                'quantity' => $quantity,
                'cost' => null,
                'balance_before' => $balanceBefore,
                'balance_after' => $stock->quantity,
                'user_id' => $userId,
                'notes' => $notes,
            ]);

            DB::commit();

            \Log::info("Stock returned: {$type} {$itemId} +{$quantity} to branch {$branchId}. New balance: {$stock->quantity}");

            return $stock;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to return stock: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Add stock for any item
     */
    public static function addStock($branchId, $type, $itemId, $quantity, $userId, $cost = null, $notes = null)
    {
        DB::beginTransaction();
        try {
            $stockableType = self::getStockableType($type);

            // Get or create stock record
            $stock = BranchStock::firstOrCreate(
                [
                    'branch_id' => $branchId,
                    'stockable_type' => $stockableType,
                    'stockable_id' => $itemId,
                ],
                [
                    'product_id' => $type === 'product' ? $itemId : null,
                    'quantity' => 0,
                    'min_quantity' => $type === 'lens' ? 2 : 5,
                    'max_quantity' => $type === 'lens' ? 50 : 100,
                ]
            );

            $balanceBefore = $stock->quantity;

            // Add quantity
            $stock->addQuantity($quantity, $cost);

            // Create movement
            StockMovement::create([
                'branch_id' => $branchId,
                'product_id' => $type === 'product' ? $itemId : null,
                'stockable_type' => $stockableType,
                'stockable_id' => $itemId,
                'type' => 'in',
                'quantity' => $quantity,
                'cost' => $cost,
                'balance_before' => $balanceBefore,
                'balance_after' => $stock->quantity,
                'user_id' => $userId,
                'notes' => $notes,
            ]);

            DB::commit();
            return $stock;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reduce stock for any item
     */
    public static function reduceStock($branchId, $type, $itemId, $quantity, $userId, $reason = null, $notes = null)
    {
        DB::beginTransaction();
        try {
            $stock = self::getStock($branchId, $type, $itemId);

            if (!$stock || $stock->available_quantity < $quantity) {
                throw new \Exception("Insufficient stock for {$type}");
            }

            $balanceBefore = $stock->quantity;

            // Reduce quantity
            $stock->reduceQuantity($quantity);

            // Create movement
            $stockableType = self::getStockableType($type);

            StockMovement::create([
                'branch_id' => $branchId,
                'product_id' => $type === 'product' ? $itemId : null,
                'stockable_type' => $stockableType,
                'stockable_id' => $itemId,
                'type' => 'out',
                'quantity' => $quantity,
                'reason' => $reason,
                'balance_before' => $balanceBefore,
                'balance_after' => $stock->quantity,
                'user_id' => $userId,
                'notes' => $notes,
            ]);

            DB::commit();
            return $stock;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reserve stock (for pending invoices)
     */
    public static function reserveStock($branchId, $type, $itemId, $quantity)
    {
        $stock = self::getStock($branchId, $type, $itemId);

        if (!$stock || $stock->available_quantity < $quantity) {
            throw new \Exception("Cannot reserve: insufficient stock");
        }

        $stock->reserveQuantity($quantity);
        return $stock;
    }

    /**
     * Unreserve stock
     */
    public static function unreserveStock($branchId, $type, $itemId, $quantity)
    {
        $stock = self::getStock($branchId, $type, $itemId);

        if ($stock) {
            $stock->unreserveQuantity($quantity);
        }

        return $stock;
    }

    /**
     * Get item details (product or lens)
     */
    public static function getItem($type, $itemId)
    {
        if ($type === 'product') {
            return Product::find($itemId);
        } else {
            return glassLense::find($itemId);
        }
    }

    /**
     * Get stockable type class name
     */
    protected static function getStockableType($type)
    {
        return $type === 'product' ? 'App\\Product' : 'App\\glassLense';
    }

    /**
     * Bulk reduce stock (for invoice items)
     */
    public static function reduceStockBulk($branchId, $items, $userId, $referenceType = null, $referenceId = null)
    {
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $type = $item['type']; // 'product' or 'lens'
                $itemId = $item['id'];
                $quantity = $item['quantity'];

                self::reduceStock(
                    $branchId,
                    $type,
                    $itemId,
                    $quantity,
                    $userId,
                    'Sale',
                    "Invoice #{$referenceId}"
                );
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get all stock for a branch (products + lenses)
     */
    public static function getBranchStock($branchId, $type = null)
    {
        $query = BranchStock::where('branch_id', $branchId)
            ->with('stockable');

        if ($type === 'product') {
            $query->where('stockable_type', 'App\\Product');
        } elseif ($type === 'lens') {
            $query->where('stockable_type', 'App\\glassLense');
        }

        return $query->get();
    }

    /**
     * Get low stock items
     */
    public static function getLowStock($branchId, $type = null)
    {
        $query = BranchStock::where('branch_id', $branchId)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->where('quantity', '>', 0)
            ->with('stockable');

        if ($type === 'product') {
            $query->where('stockable_type', 'App\\Product');
        } elseif ($type === 'lens') {
            $query->where('stockable_type', 'App\\glassLense');
        }

        return $query->get();
    }
}
