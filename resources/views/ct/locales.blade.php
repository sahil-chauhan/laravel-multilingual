@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-8 col-md-offset-4">
        <h2>All Locales</h2>
         <a class="btn btn-primary" href="{{ url('/translation/add/locale')  }}">Add Locale</a>
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>Sno</th>
                  <th>Locale Name</th>
                  <th>Locale Slug</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @if( $all_locales )                    
                    @foreach( $all_locales as $locale)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $locale->locale_name }}</td>
                            <td>{{ $locale->locale_slug }}</td>                            
                            <td>
                                <a class="btn btn-info" href="{{ url('/translation/edit/locale/'.$locale->id) }}">Edit</a>
                                <a class="btn btn-danger" href="{{ url('/translation/delete/locale/'.$locale->id) }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach   
                @endif     
            </tbody>
         </table>
      </div>      
   </div>
</div>
@endsection