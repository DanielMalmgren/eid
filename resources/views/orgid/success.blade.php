@extends('layouts.app')

@section('content')

<script type="text/javascript">

    $(function() {

        function poll() {
            $.ajax({
                url: '/orgidResult?reference={{$reference}}&organization={{$asuser->organization}}',
                dataType:"json",
                type: 'GET',
                success: function(data) {
                    var again = false;
                    switch(data.status) {
                        case "STARTED":
                        case "DELIVERED_TO_MOBILE":
                            again = true;
                            break;
                        case "CANCELED":
                        case "RP_CANCELED":
                        case "EXPIRED":
                            $('#feedback').html("Aktiveringen avbröts!");
                            break;
                        case "APPROVED":
                            $('#feedback').html("Ditt tjänste-ID är nu färdigt att användas!");
                            break;
                        default:
                            $('#feedback').html("Oväntat fel!");
                            break;
                    }
                    if(again) {
                        setTimeout(poll,1000);
                    }
                },
                error: function() {
                    console.log("ERROR");
                }
            });
        }

        poll();
    });
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Aktivering av tjänste-ID</div>
                <div class="card-body">
                    <div id="feedback">
                        Du behöver nu godkänna aktiveringen av ditt nya tjänste-ID.<br>
                        Detta gör du i Freja-appen i din telefon.<br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
