@extends('layouts.theme.app')

@section('content')
    <div class="container">
        <div class="row">
            @foreach($links as $link)
                <div class="col-sm-12 col-md-3 mb-4">
                    <div class="card hover-effect">
                        <div class="card-body">
                            <a href="{{ $link['url'] }}" class="menu-toggle text-decoration-none" data-active="false"
                                @if(isset($link['target'])) target="{{ $link['target'] }}" @endif>
                                <div class="base-menu d-flex align-items-center" style="gap: 1rem; flex-direction: column;">
                                    <div class="base-icons">
                                        {!! $link['icon'] !!}
                                    </div>
                                    <span class="font-weight-bold">{{ $link['title'] }}</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .hover-effect {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }

        .hover-effect:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: #007bff;
        }

        .hover-effect .card-body {
            transition: all 0.3s ease;
        }

        .hover-effect:hover .card-body {
            background-color: #f8f9fa;
        }

        .hover-effect:hover .feather,
        .hover-effect:hover .lucide {
            stroke: #007bff;
        }

        .hover-effect:hover span {
            color: #007bff;
        }

        .base-icons {
            width: 56px;
            height: 56px;
        }
    </style>
@endsection