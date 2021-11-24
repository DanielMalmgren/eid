@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Antal tjänste-ID per kommun</div>

                <div class="card-body">

                    @foreach($organizations as $name => $amount)
                        {{$name}}: {{$amount}}<br>
                    @endforeach
                    <p class="font-weight-bold">
                        Totalt: {{$organizations->sum()}}
                    </p>

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
