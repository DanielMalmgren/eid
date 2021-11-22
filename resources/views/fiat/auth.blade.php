@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Itsam <-> Freja Authentication Tool</div>

                <div class="card-body">

                    Du kommer att utföra en autentisering mot nedanstående användare:<br><br>

                    Namn: {{$user->name}}<br>
                    Användarnamn: {{$user->username}}<br>
                    Kommun: {{$user->organization}}<br>
                    Titel: {{$user->title}}<br><br>

                    @if($hasOrgId)
                        <a href="#" onClick="auth()" class="btn btn-primary">Autentisera med tjänste-ID</a>
                    @else
                        Användaren saknar tjänste-id för {{$user->organization}}!<br>
                        <a href="#" onClick="auth()" class="btn btn-primary disabled">Autentisera med Freja eID+</a><br>
                        (Inte färdig med denna funktion än. Men den funkar för tjänste-ID! /Daniel)
                    @endif

                    <br><br>

                    <div style="font-size:x-large" id="result"></div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function auth() {
        $.ajax({
            url: '/fiat/orgIdAuthAjax/{{$user->username}}',
            dataType:"text",
            type: 'GET',
            success: function (data) {
                $('#result').html("Väntar på autentisering...");
                $('#result').css('color', 'black');
                authResult(data);
            },
        });
    }

    function authResult(authRef) {
        $.ajax({
            url: '/fiat/result/'+authRef+'?organization={{$user->organization}}',
            dataType:"text",
            type: 'GET',
            success: function(data) {
                var again = false;
                switch(data) {
                    case "STARTED":
                    case "DELIVERED_TO_MOBILE":
                        again = true;
                        break;
                    case "CANCELED":
                    case "RP_CANCELED":
                    case "EXPIRED":
                        $('#result').html("Autentiseringen avbröts!");
                        $('#result').css('color', 'red');
                        break;
                    case "APPROVED":
                        $('#result').html("Autentiseringen är godkänd!");
                        $('#result').css('color', 'green');
                        break;
                    default:
                        $('#result').html("Oväntat fel!");
                        $('#result').css('color', 'red');
                        break;
                }
                if(again) {
                    setTimeout(authResult,2000,authRef);
                }
            },
            error: function() {
                $('#result').html("Oväntat fel!");
                $('#result').css('color', 'red');
                console.log("ERROR");
            }
        });
    }
</script>

@endsection
