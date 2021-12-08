@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if($user->isAdmin)
                <div class="card">
                    <div class="card-body">

                        Ange användarnamn nedan.<br><br>

                        <form method="post" action="{{action('HomeController@index')}}" accept-charset="UTF-8">
                            @csrf

                            <div class="mb-3">
                                <input id="username" name="username" maxlength="10" class="form-control">
                            </div>

                            <button class="btn btn-primary" type="submit">Ändra användare för aktivering</button>
                            <a href="#" class="btn btn-primary" onclick="fiat()">Autentisera användare</a>
                        </form>


                    </div>
                </div>
                <br>
            @endif

            <div class="card">
                <div class="card-header">Välkommen till Itsams aktivering av digitalt tjänste-ID!</div>

                <div class="card-body">

                    <form method="post" action="{{action('HomeController@orgid')}}" accept-charset="UTF-8">
                        @csrf

                        <input type="hidden" name="username" value="{{$user->username}}">

                        Ditt tjänste-ID kommer att få följande uppgifter:<br>
                        Namn: {{$asuser->name}}<br>
                        Användarnamn: {{$asuser->username}}<br>

                        @if (count($asuser->organizations) === 1)
                            Kommun: {{$asuser->organizations[0]}}<br>
                            <input type="hidden" name="organization" value="{{$asuser->organizations[0]}}">
                        @else
                            <div class="form-row">
                                <div class="col-2">
                                    <label>Kommun:</label>
                                </div>
                                <div class="col-4">
                                    <select class="form-control form-control-sm" name="organization" required="">
                                        @foreach($asuser->organizations as $organization)
                                            <option>{{$organization}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if($user->isAdmin)
                            <div class="form-row">
                                <div class="col-1">
                                    <label>Titel:</label>
                                </div>
                                <div class="col-4">
                                    <input required minlength="5" name="title" maxlength="22" class="form-control form-control-sm" value="{{$asuser->title}}">
                                </div>
                            </div>
                        @else
                            Titel: {{$user->title}}<br><br>
                            <input type="hidden" name="title" value="{{$asuser->title}}">
                        @endif

                        <br>

                        Om dessa uppgifter är korrekta, klicka på knappen nedan!<br><br>

                        <button class="btn btn-primary" type="submit">Aktivera tjänste-ID</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">

    function fiat() {
        var username=document.getElementById("username").value;
        window.location='/fiat/'+username;
    }

</script>

@endsection
