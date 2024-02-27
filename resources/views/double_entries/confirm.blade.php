<div class="card-body">
    <div class="mb-12 form-group opening_balance">
        <label for="email">
            <span><b>{{ __('Entry ID') }}:</b></span>
            {{ $confirm->entry_id }}
        </label>
        <label for="email">
            <span><b>{{ __('Date') }}:</b></span>
            {{-- {{ $confirm->date}} --}}
            {{ date('d-M-Y', strtotime($confirm->date)) }}
        </label>
        <label for="email">
            <span><b>{{ __('Description') }}:</b></span>
            {{ $confirm->description }}
        </label>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered" id="table1">
        <thead>
            <tr>
                <th>{{ __('Ledger Account') }}</th>
                @if (!count(array_filter($confirm->share)) == 0)
                    <th>{{ __('Share') }}</th>
                @endif
                <th>{{ __('Particular') }} </th>
                <th>{{ __('Status') }} </th>
                <th>{{ __('Amount') }} </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($confirm->particular as $key => $particular)
                <tr>
                    <td>{{ App\Models\LedgerAccount::find($confirm->ledger_ac_id[$key])->account_name }}</td>
                    @if (!count(array_filter($confirm->share)) == 0)
                        <td>{{ $confirm->share[$key] }}</td>
                    @endif
                    <td>{{ $confirm->particular[$key] }}</td>
                    <td>{{ $confirm->type[$key] }}</td>
                    <td>{{ $confirm->amount[$key] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-body">
    <div class="mb-12 form-group opening_balance">
        <p style="color:red"><b>{{ __('NOTE') }}: </b>{{ __('The details of this double entry cannot be updated. Please confirm the information and then save it.') }}</p>
    </div>
</div>

<div class="mb-3 row justify-content-center modal-footer" style="text-align: justify;">
    <button type="button" class="col-md-1 offset-md-5 btn btn-primary" data-bs-dismiss="modal">{{ __('no') }}</button>
    <button type="button" class="col-md-1 offset-md-5 btn btn-primary" onclick="$('#myForm').submit();"><i class="fa fa-lock"></i>{{ __('save') }}</button>
</div>
