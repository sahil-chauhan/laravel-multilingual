@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-8 col-md-offset-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('success'))
           <div class="alert alert-info">{{ Session::get('success') }}</div>
        @endif
         @if (Session::has('error'))
           <div class="alert alert-warning">{{ Session::get('error') }}</div>
        @endif
      </div>
      <div class="col-md-6 col-md-offset-6">
        <h2>Add Translation</h2>
        <form action="{{ url('/translation/update/key') }}" method="POST">
          {{ csrf_field() }}
            <input type="hidden" name="trans_key_id" value="{{ $CT->id }}">
            <div class="form-group">
              <label>Translation Key</label>
              <input type="" class="form-control"  name="trans_key" value="{{ $CT->trans_key }}" >
            </div> 
            @if( $all_locales )
              @foreach($all_locales as $locale)
                <div class="form-group">
                  <label>Value For {{ $locale->locale_name }}</label>
                  @if( count($CT_meta) )
                    @php
                      $hit = 0 ;
                    @endphp
                    @foreach($CT_meta as $meta)
                      @if( $meta->locale_id == $locale->id)
                        <input type="text" class="form-control" name="trans_key_value[{{ $locale->id }}][]" value="{{ $meta->trans_key_value }}">                      
                        @php
                          $hit = 1;
                        @endphp
                      @endif
                    @endforeach
                    @if( $hit == 0 )
                        <input type="text" class="form-control" name="trans_key_value[{{ $locale->id }}][]" value="">                
                    @endif
                  @endif
                </div>
              @endforeach
            @endif     
            <button class="btn btn-primary">Submit</button>  
        </form>
      </div>
   </div>
</div>
@endsection