<?php

namespace App\Observers;

use App\Models\Uthang;
use App\Models\Item;
use App\Models\Limit;
use App\Models\Debtors;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class UthangsObserver
{
    public function creating(Uthang $uthang)
    {
        $itemPrice = Item::where('item_id', $uthang->item_id)->value('price');
        $totalDebtDId = Uthang::where('d_id', $uthang->d_id)->sum('total');
        $inventory_limit = Limit::where('id', 1)->value('amount');
        $limitPerDebtor = Limit::where('id', 2)->value('amount');
        
        
        if ($uthang->quantity * $itemPrice > 1000) {
            throw ValidationException::withMessages(['error' => 'Total price exceeds 1000 for this item']);
        }
        if ($totalDebtDId + ($uthang->quantity * $itemPrice) > $limitPerDebtor) {
            throw ValidationException::withMessages(['error' => 'Exceeded maximum total of Debt for this debtor']);
        }

        $totalPrice = Uthang::where('item_id', $uthang->item_id)->sum('total');
        $totalDebt = Uthang::sum('total');

        if ($uthang->total + $totalDebt > 3000) {
            throw ValidationException::withMessages(['error' => 'Exceeded maximum total of Debt']);
        }
    }

    public function updated(Uthang $uthang)
    {
        $changeText = '';

        if ($uthang->isDirty(['quantity', 'item_id'])) {
            $oldItemName = Item::where('item_id', $uthang->getOriginal('item_id'))->value('item_name');
            $newItemName = Item::where('item_id', $uthang->item_id)->value('item_name');
            $oldDebtorName = Debtors::where('d_id', $uthang->d_id)->value('d_name');

            $changeText = 'Edited ' . $oldItemName . ' x' . $uthang->getOriginal('quantity') . ' to ' . $newItemName . ' x' . $uthang->quantity;
        }

        if (!empty($changeText)) {
            History::create([
                'transaction' => $changeText,
                'd_id' => $uthang->d_id,
                'name' => $oldDebtorName,
                'date' => now(),
            ]);
        }
    }
}
