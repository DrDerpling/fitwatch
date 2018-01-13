@extends('layouts.app')

@section('content')

    <div class="container">



        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Gebruikers informatie</div>

                    <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img class="img-circle" src="{{$fitbit->fitbitStats->avatar}}">
                                </div>
                                <div class="col-md-8">
                                    <h4>{{$fitbit->fitbitStats->full_name}}</h4>
                                    <span class="col-md-7 no-padding"><strong>Leeftijd:</strong></span>
                                    <span class="col-md-5 text-align--right">{{$fitbit->fitbitStats->age}}</span>
                                    <span class="col-md-6 no-padding"><strong>Gewicht:</strong></span>
                                    <span class="col-md-6 text-align--right">{{$fitbit->fitbitStats->weight}} KG</span>
                                    <span class="col-md-7 no-padding"><strong>Gem stappen:</strong></span>
                                    <span class="col-md-5 text-align--right">{{$fitbit->fitbitStats->average_daily_steps}}</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="underline">Over mij</h4>
                                    {{$fitbit->fitbitStats->about_me}}
                                </div>
                            </div>
                    </div>
                </div></div>
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">Setup</div>
                    <img src="{{asset('/storage/svg/loading.svg')}}" >

                    <div class="panel-body"> </div>
                </div>
            </div>
        </div>
    </div>

@endsection