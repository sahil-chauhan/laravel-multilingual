<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\customLocale;
class globalViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
    	$all_locales = customLocale::get();
        $view->with('all_locales', $all_locales);
    }
}