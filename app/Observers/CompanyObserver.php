<?php

namespace App\Observers;

use App\Models\Company;

class CompanyObserver
{
    /**
     * Handle the company "created" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function created(Company $company)
    {
        //
    }

    /**
     * Handle the company "updated" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function updated(Company $company)
    {
        //
    }

    /**
     * Handle the company "saved" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function saved(Company $company)
    {
        // Handle image upload
        if (request()->photo && !request()->company_photo_updated) {
            // Prevents infinite loop on save
            request()->company_photo_updated = true;

            // Delete image
            if (request()->photo=='delete') {
                $media = $company->getFirstMedia('companies');
                $media? $media->delete() : null;
                $company->photo = null;

            // Add image from file (web)
            } elseif (file_exists(request()->photo)) {
                $media = $company->addMedia(request()->photo)->toMediaCollection('companies');
                $company->photo = $media->getFullUrl();

            // Add image from base64 (api)
            } else {
                $media = $company->addMediaFromBase64(request()->photo)->toMediaCollection('companies');
                $company->photo = $media->getFullUrl();
            }

            // Save changes
            $company->save();
        }
    }

    /**
     * Handle the company "deleted" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function deleted(Company $company)
    {
        //
    }

    /**
     * Handle the company "restored" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function restored(Company $company)
    {
        //
    }

    /**
     * Handle the company "force deleted" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function forceDeleted(Company $company)
    {
        //
    }
}