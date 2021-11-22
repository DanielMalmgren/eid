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

                            <button class="btn btn-primary" type="submit">Aktivera tjänste-ID</button>
                            <a href="#" class="btn btn-primary" onclick="fiat()">Autentisera användare</a>
                        </form>


                    </div>
                </div>
                <br>
            @endif

            <div class="card">
                <div class="card-header">Välkommen till Itsams aktivering av digitalt tjänste-ID!</div>

                <div class="card-body">

                    För att gå vidare behöver du ha ett Freja eID+.<br>
                    Om du har det, klicka på "Aktivera tjänste-ID" nedan.<br><br>

                    Ditt tjänste-ID kommer att ha följande uppgifter:<br>
                    Namn: {{$user->name}}<br>
                    Användarnamn: {{$user->username}}<br>
                    Kommun: {{$user->organization}}<br>
                    Titel: {{$user->title}}<br><br>

                    <a href="/orgid?username={{$user->username}}" class="btn btn-primary">Aktivera tjänste-ID</a>

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
