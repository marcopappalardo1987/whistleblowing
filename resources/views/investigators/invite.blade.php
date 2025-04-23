<x-app-layout>
    
    <x-slot name="header">
        {{ __('Invita un investigatore') }}
    </x-slot>

    @include('layouts.alert-message')  

    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    @if ($countInvestigators >= 1)
                        <div class="alert alert-danger">
                            {{ __('Hai raggiunto il limite massimo di investigatori.') }}
                        </div>
                        <a href="{{ route('investigator.list') }}" class="btn btn-primary" style="width: auto;">{{ __('Vai all\'elenco degli investigatori') }}</a>
                    @else
                        <form action="{{ route('investigator.invite.store') }}" method="POST" class="mt-2">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">{{ __('Nome') }}</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="branch_id" class="form-label">{{ __('Branch') }}</label>
                                    <select name="branch_id" id="branch_id" class="form-control">
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>  
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4 small background-color5 p-3 rounded-3">
                                {!! __('Quando inviti un <strong>investigatore</strong>, la persona che hai invitato riceverà una <em>email di invito</em> per la <strong>gestione delle segnalazioni</strong>.') !!}
                                <br>
                                {!! __('Per accettare l\'invito, la persona dovrà cliccare sul <strong>link</strong> presente nell\'<em>email di invito</em> e <strong>registrarsi</strong> con la stessa email che hai utilizzato per l\'invito.') !!}
                                <br>
                                {!! __('Una volta <strong>registrata</strong>, la persona potrà <em>accedere al software</em> utilizzando la stessa email e la <strong>password</strong> che hai impostato.') !!}
                            </div>
                            <button type="submit" class="btn btn-primary mb-3">{{ __('Invita') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
