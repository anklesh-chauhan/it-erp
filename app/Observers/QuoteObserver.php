<?php 

namespace App\Observers;

use App\Models\Quote;
use App\Models\TermsAndConditionsMaster;
use App\Models\TermsAndCondition;

class QuoteObserver
{
    public function created(Quote $quote)
    {
        // $masters = TermsAndConditionsMaster::where('document_type', 'quote')->get();

        // foreach ($masters as $master) {
        //     TermsAndCondition::create([
        //         'model_id'   => $quote->id,
        //         'model_type' => Quote::class,
        //         'title'      => $master->title,
        //         'content'    => $master->content,
        //     ]);
        // }
    }
}
