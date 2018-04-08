@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
        <h2>All Translations</h2>
        @if (Session::has('success'))
           <div class="alert alert-info">{{ Session::get('success') }}</div>
        @endif
        <a class="btn btn-primary" href="{{ url('/translation/add/key')  }}">Add New Translation</a>
        <a class="btn btn-info pull-right" href="{{ url('/translation/sync')  }}">Sync with files</a>       
        
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>Sno</th>
                  <th>Translation Key</th>
                  <th>Translation value</th>                 
                  <th>Action</th>                 
               </tr>
            </thead>
            <tbody>
              @if( count($translations) )
                @foreach($translations as $translation)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $translation->trans_key }}</td>
                    <td>
                      @php
                        $trans_meta = \App\customTranslationMeta::join('custom_locales','custom_translation_metas.locale_id','=','custom_locales.id')->where('custom_translation_metas.trans_key_id',$translation->id)->get();
                      @endphp                   
                      @if( count($trans_meta) )
                          <?php 
                            $thead ="<tr>";
                            $tbody ="<tr>";
                              foreach($trans_meta as $t_meta)
                              {
                                  $thead .="<th>".$t_meta->locale_name."</th>";
                                  $tbody .="<td>".$t_meta->trans_key_value."</td>";
                              }  
                            $thead .="</tr>";      
                            $tbody .="</tr>";      
                          ?>
                          <table class="table table-bordered">
                            <?php echo $thead;?>
                            <?php echo $tbody;?>
                          </table>                
                      @endif
                    </td>
                    <td>
                      <a  href="{{ url('/translation/edit/key/'.$translation->id) }}" class="btn btn-info">Edit</a>
                      <a  href="{{ url('/translation/delete/key/'.$translation->id) }}" class="btn btn-danger">Delete</a>
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
         </table> 
         <div style="float:right;">
           {{ $translations->links() }}
         </div>
      </div>      
   </div>
</div>
@endsection