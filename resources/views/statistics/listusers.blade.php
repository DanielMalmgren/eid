@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Användare med tjänste-id i {{$municipality}}</div>

                <div class="card-body">

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Användarnamn</th>
                                <th scope="col">Titel</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($orgids as $orgid)
                                <tr>
                                    <td>{{$orgid->organisationId->identifier}}</td>
                                    <td>{{$orgid->organisationId->title}}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <p class="font-weight-bold">
                        Totalt: {{$count}}
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
