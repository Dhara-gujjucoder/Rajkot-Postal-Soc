@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-loan_matrix')
                <a href="{{ route('loan_matrix.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{ __('Add New Loan') }}</a>
            @endcan
            <div class="pt-4 mt-5">
                <table class="table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Minimum EMI') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan_matrixs as $key => $loan)
                        <tr>
                            <td>{{ $loan->id }}</td>
                            <td>{{ $loan->amount }}</td>
                            <td>{{ $loan->minimum_emi }}</td>
                            <td>{{ $loan->status == 1 ? __('Active') : __('Deactive') }} </td>
                            <td>
                                <form action="{{ route('loan_matrix.destroy', $loan->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                        @can('edit-loan_matrix')
                                            <a href="{{ route('loan_matrix.edit', $loan->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                    class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                        @endcan

                                        @can('delete-loan_matrix')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm(`{{__('Do you want to delete this Loan Entry?')}}`);"><i
                                                        class="bi bi-trash"></i> {{__('Delete')}}</button>
                                        @endcan

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
@endsection
@push('script')
<script>
    $(function() {
        var table = $('#table1').DataTable({
        });
    });
</script>
@endpush
