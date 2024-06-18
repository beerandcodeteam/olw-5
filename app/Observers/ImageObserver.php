<?php

namespace App\Observers;

use App\Models\Image;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Storage;

class ImageObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Image "created" event.
     */
    public function created(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "updated" event.
     */
    public function updated(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "deleted" event.
     */
    public function deleted(Image $image): void
    {
        dump("oi");
        Storage::delete($image->url);
    }

    /**
     * Handle the Image "restored" event.
     */
    public function restored(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "force deleted" event.
     */
    public function forceDeleted(Image $image): void
    {
        //
    }
}
