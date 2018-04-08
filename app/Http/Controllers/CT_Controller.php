<?php

namespace App\Http\Controllers;

use App\customTranslation;
use App\customLocale;
use App\customTranslationMeta;
use Illuminate\Http\Request;
use Session;
use App;
use Validator;
use DB;

class CT_Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $translations =  customTranslation::orderBy('id', 'desc')->paginate(5);
        return view("ct.index",compact('translations'));
    }
    public function showLocales()
    {
        return view("ct.locales");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\customTranslation  $customTranslation
     * @return \Illuminate\Http\Response
     */
    public function show(customTranslation $customTranslation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customTranslation  $customTranslation
     * @return \Illuminate\Http\Response
     */
    public function edit(customTranslation $customTranslation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\customTranslation  $customTranslation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customTranslation $customTranslation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\customTranslation  $customTranslation
     * @return \Illuminate\Http\Response
     */
    public function destroy(customTranslation $customTranslation)
    {
        //
    }

    public function changeLocale($locale)
    {
        $allLocales = customLocale::get()->pluck('locale_slug');

        $allLo = json_decode(json_encode($allLocales),true);

        if( in_array($locale,$allLo) )
        {
            Session::put('locale',$locale);
            App::setLocale($locale);
        }
        return redirect()->back();
    }
    public function addLocale()
    {
        return view('ct.addlocale');
    }
    public function createLocale(Request $request)
    {
        $rules = [
            'locale_name' => 'required',
            'locale_slug' => 'required|unique:custom_locales|slug',
        ];
        $messages = [            
            'locale_slug.unique' => 'Slug must be unique',
            'locale_slug.slug' => 'Slug is not valid',
        ];
        Validator::extend('slug', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        });
        $validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()) {
            return redirect()
                    ->back()    
                    ->withErrors($validator)
                    ->withInput();
        }

        $locale_name = $request->input('locale_name');
        $locale_slug = $request->input('locale_slug');

        $lan_dir_path = base_path().'/resources/lang/'.$locale_slug;
        
        if( !is_dir($lan_dir_path) )
        {
            mkdir("$lan_dir_path");
        }
        $locale = new customLocale;
        $locale->locale_name = $locale_name;
        $locale->locale_slug = $locale_slug;
        $locale->save();
        return redirect()->back()->with('success','Locale Created Succesfuly.');
    }

    public function editLocale($locale)
    {
        $lan_locale = customLocale::find($locale);
        if( !$lan_locale)
        {
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function deleteLocale($locale)
    { 
       $lan_locale = customLocale::find($locale);
        if( !$lan_locale)
        {
            return redirect()->back();
        }
        $locale_slug = $lan_locale->locale_slug;
        $lan_dir_path = base_path().'/resources/lang/'.$locale_slug;
        if( is_dir($lan_dir_path) )
        {
            system("rm -rf $lan_dir_path");
        }       
        customTranslationMeta::where('locale_id','=',$lan_locale->id)->delete(); 
        $lan_locale->delete();
        return redirect()->back()->with('success','Locale Deleted Succesfuly.');;
    }

    public function addTransKey()
    {
        $locales = customLocale::get();
        return view('ct.addTransKey', compact('locales') );
    }

    public function storeTransKey(Request $request)
    {
        
        $rules = [
            'trans_key' => 'required|unique:custom_translations|slug',           
        ];
        $messages = [   
            'trans_key.required' => 'Field is required',   
            'trans_key.slug' => 'Key is not valid',
            'trans_key.unique' => 'Key is already used',
        ];
        Validator::extend('slug', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        });
        $validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()) {
            return redirect()
                    ->back()    
                    ->withErrors($validator)
                    ->withInput();
        }
        
        $trans_key = $request->input('trans_key');
        $trans_value = $request->input('trans_key_value');
      
        $tnsky = new customTranslation;
        $tnsky->trans_key = $trans_key;
        $tnsky->save();

        if( count($trans_value) )
        {
            foreach ($trans_value as $key => $value) 
            {
               $ctnsmeta = new customTranslationMeta;
               $ctnsmeta->locale_id = $key;
               $ctnsmeta->trans_key_id = $tnsky->id;
               $ctnsmeta->trans_key_value = $value[0];
               $ctnsmeta->save();
            }
        }

        return redirect()->back();
    }
    public function syncroniseTranslation()
    {
        $custom_locales = customLocale::get();
        $trans_data = [];
        if( $custom_locales )
        {
            foreach ($custom_locales as $key => $value)
            {
                $translations =  DB::table('custom_translation_metas')
                    ->join('custom_translations', 'custom_translation_metas.trans_key_id', '=', 'custom_translations.id')                   
                    ->where('locale_id',$value->id)
                    ->get();               
                if( $translations )
                {
                    foreach ($translations as $key => $translation)
                    {
                        $trans_data[$value->locale_slug][$translation->trans_key] = $translation->trans_key_value;
                    }
                }
                
            }
        }
        if( count($trans_data) )
        {
            foreach ($trans_data as $key => $t_data) 
            {
                
                $lan_dir_path = base_path().'/resources/lang/'.$key;        
                if( !is_dir($lan_dir_path) )
                {
                    mkdir("$lan_dir_path");
                }              

                $tem_str = "";

                $tem_str .= "<?php \n";
                $tem_str .= "\n";
                $tem_str .= "return [";
                $tem_str .= "\n";
                $tem_str .= "\n";

                if( count($t_data) )
                {
                    foreach( $t_data as $kk=>$data_string )
                    {
                        $tem_str .= "'".$kk."' => '".self::sanitize_string_data($data_string)."', \n";
                        
                    }
                } 

                $tem_str .= "\n";
                $tem_str .= "];";

                $file_data  =  $tem_str;
                $filePath = $lan_dir_path.'/message.php';
                file_put_contents($filePath,$file_data);
            }
        }
        return redirect()->back();
    }

    public function sanitize_string_data($str='')
    {
        return str_replace("'","\'",$str);
    }

    public function editTransKey($id)
    {
        $CT = customTranslation::find($id);
        $CT_meta = customTranslationMeta::where('trans_key_id',$id)->get();

        return view('ct.editTransKey',compact('CT','CT_meta'));
    }

    public function updateTransKey(Request $request)
    {
        // dd($request);
        $rules = [
            'trans_key' => 'required|slug',           
        ];
        $messages = [   
            'trans_key.required' => 'Field is required',   
            'trans_key.slug' => 'Key is not valid',
        ];
        Validator::extend('slug', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        });

        $validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()) {
            return redirect()
                    ->back()    
                    ->withErrors($validator)
                    ->withInput();
        }

        $trans_key_id = $request->input('trans_key_id');
        $trans_key = $request->input('trans_key');
        $trans_key_value = $request->input('trans_key_value');

        $check = customTranslation::where('id','!=',$trans_key_id)->where('trans_key','=',$trans_key)->get();

        if( count($check) )
        {
            return redirect()->back()->with('error','This key is already in use.');
        }

        $ctk = customTranslation::find($trans_key_id);
        $ctk->trans_key = $trans_key;
        $ctk->save();


        if( count($trans_key_value) )
        {
            foreach ($trans_key_value as $key => $value) 
            {
               $is_key_exists = customTranslationMeta::where('locale_id','=',$key)->where('trans_key_id','=',$ctk->id)->first();
               if( $is_key_exists )
               {
                    $is_key_exists->trans_key_value = $value[0];
                    $is_key_exists->save();
               }else
               {
                    $ctnsmeta = new customTranslationMeta;
                    $ctnsmeta->locale_id = $key;
                    $ctnsmeta->trans_key_id = $ctk->id;
                    $ctnsmeta->trans_key_value = $value[0];
                    $ctnsmeta->save();
               }
            }
        }
        return redirect()->back()->with('success','Translations are updated.');
    }

    public function deleteTransKey($id)
    {
        $ctk = customTranslation::find($id);
        customTranslationMeta::where('trans_key_id','=',$ctk->id)->delete();
        $ctk->delete();
        return redirect()->back()->with('success','Translations key is removed.');
    }
}
