<?php

namespace App\Observers;

use App\Models\ManufacturingPart;
use Illuminate\Support\Facades\Auth;

class ManufacturingPartObserver
{
    public function creating(ManufacturingPart $manufacturingPart): void
    {
        $manufacturingPart->user_id= Auth::user()->id;

    }
    /**
     * Handle the ManufacturingPart "created" event.
     */
    public function created(ManufacturingPart $manufacturingPart): void
    {
        $manufacturingPart->user_id =  Auth::user()->id;
    }

    /**
     * Handle the ManufacturingPart "updated" event.
     */
    public function updated(ManufacturingPart $manufacturingPart): void
    {
        $manufacturingPart->user_id =  Auth::user()->id;
    }


}
