@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Cadastrar Imagem') }}</span>
                        <small>Cadastrando imagem como: <strong>{{ (Auth::check()) ? Auth::user()->name : 'Anônimo' }}</strong></small>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('images.form', ['image' => $image])
                        <div class="mt-3">
                            <a href="{{ route('images') }}" class="btn btn-danger">
                                {{ __('Voltar') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Salvar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection