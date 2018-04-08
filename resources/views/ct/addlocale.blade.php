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
      <div class="col-md-8 col-md-offset-4">
        <form action="{{ url('/translation/add/locale') }}" method="POST">
          {{ csrf_field() }}
            <div class="form-group">
              <label for="l_name">Locale Name</label>
              <input type="text" class="form-control" id="l_name" name="locale_name" value="{{ old('locale_name') }}">
            </div>
            <div class="form-group">
              <label for="l_slug">Locale Slug</label>
              <input type="text" class="form-control" id="l_slug" name="locale_slug" value="{{ old('locale_slug') }}">
            </div>  
            <button class="btn btn-primary">Submit</button>  
        </form>
      </div>
   </div>
</div>
@endsection