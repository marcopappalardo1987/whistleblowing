<div class="content-page mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="h5 card-title mb-3">{{ __('Elenco delle Commissioni') }}</h3>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Livello') }}</th>
                                <th scope="col">{{ __('Commissione (%)') }}</th>
                                <th scope="col">{{ __('Azioni') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affiliateCommission as $commission)
                                <tr>
                                    <td>{{ $commission->level }}</td>
                                    <td>{{ $commission->commission }}</td>
                                    <td>
                                        <a href="{{ route('affiliate.settings.commissions.edit', $commission->id) }}" class="btn btn-sm btn-warning">{{ __('Modifica') }}</a>
                                        <form action="{{ route('affiliate.settings.commissions.destroy', $commission->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('Elimina') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
