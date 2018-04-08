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
      </div>
      <div class="col-md-6 col-md-offset-6">
        <h2>Add Translation</h2>
        <form action="{{ url('/translation/add/key') }}" method="POST">
          {{ csrf_field() }}
            <div class="form-group">
              <label>Translation Key</label>
              <input type="" class="form-control"  name="trans_key" value="{{ old('trans_key') }}" >
            </div> 
            @if( $locales )
              @foreach($locales as $locale)
                <div class="form-group">
                  <label>Value For {{ $locale->locale_name }}</label>
                  <input type="text" class="form-control" name="trans_key_value[{{ $locale->id }}][]" value="<?php echo old('trans_key_value.'.$locale->id.'.0'); ?>">
                </div>
              @endforeach
            @endif     
            <button class="btn btn-primary">Submit</button>  
        </form>
      </div>
   </div>
</div>
@endsection