<?php

namespace App\Observers;

use App\Models\Debtors;
use App\Models\History;
use Illuminate\Support\Facades\Log;



class DebtorObserver
{
    /**
     * Handle the Debtor "created" event.
     */
    public function created(Debtors $debtor): void
    {
        //
    }

    /**
     * Handle the Debtor "updated" event.
     */
    public function updated(Debtors $debtor): void
    {
        $changeText = '';

        if ($debtor->isDirty(['d_name', 'phone', 'address'])) {
            if ($debtor->getOriginal('d_name') != $debtor->d_name) {
                $changeText .= 'Edited Debtor Name ' . $debtor->getOriginal('d_name') . ' to ' . $debtor->d_name . '. ';
            }

            if ($debtor->getOriginal('phone') != $debtor->phone) {
                $changeText .= 'Edited Debtor Phone ' . $debtor->getOriginal('phone') . ' to ' . $debtor->phone . '. ';
            }

            if ($debtor->getOriginal('address') != $debtor->address) {
                $changeText .= 'Edited Debtor Address ' . $debtor->getOriginal('address') . ' to ' . $debtor->address . '. ';
            }
        }

        if (!empty($changeText)) {
            History::create([
                'transaction' => $changeText,
                'd_id' => $debtor->d_id,
                'name' => $debtor->getOriginal('d_name'),
                'date' => now(),
            ]);
        }
    }

    /**
     * Handle the Debtor "deleted" event.
     */
    public function deleted(Debtors $debtor): void
    {
        //
    }

    /**
     * Handle the Debtor "restored" event.
     */
    public function restored(Debtors $debtor): void
    {
        //
    }

    /**
     * Handle the Debtor "force deleted" event.
     */
    public function forceDeleted(Debtors $debtor): void
    {
        //
    }
}
