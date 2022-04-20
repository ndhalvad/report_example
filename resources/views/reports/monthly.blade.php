@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Monthly Report</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <table border="0" cellspacing="4" cellpadding="4">
                            <tbody><tr>
                                <td>Minimum date:</td>
                                <td><input type="text" id="min" name="min"></td>
                            
                                <td>Maximum date:</td>
                                <td><input type="text" id="max" name="max"></td>
                                <td>Country:</td>
                                <td><select name="country" id="country">
                                        <option value="">Select Country</option>
                                        @foreach($objCountry as $cntInd => $cntVal) <option value="{{$cntVal->id}}">{{$cntVal->country_name}}</option>@endforeach
                                    </select>
                                </td>
                                <td>Company:</td>
                                <td><select name="company" id="company">
                                    <option value="">Select Company</option>
                                    @foreach($objCompany as $cmpInd => $cmpVal) <option value="{{$cmpVal->id}}">{{$cmpVal->name}}</option>@endforeach
                                </select></td>
                            </tr>
                        </tbody></table>
                    </div>
                </div>
            </div>
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Company</th>
                        <th>Day</th>
                        <th>Country</th>
                        <th>Number of tests</th>
                        <th>Number of fails</th>
                        <th>Connection Score(%)</th>
                        <th>PDD Score</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            // Create date inputs
            minDate = new DateTime($('#min'), {
                format: 'YYYY-MM-DD'
            });
            maxDate = new DateTime($('#max'), {
                format: 'YYYY-MM-DD'
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('mreports') }}",
                    data:function (d) {
                        d.min_start_date = $('#min').val(),
                        d.max_start_date = $('#max').val(),
                        d.country_id = $('#country').val(),
                        d.company_id = $('#company').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'company', name: 'company'},
                    {data: 'start_time', name: 'start_time'},
                    {data: 'country', name: 'country'},
                    {data: 'no_of_test', name: 'no_of_test'},
                    {data: 'no_of_fails', name: 'no_of_fails'},
                    {data: 'conn_rate', name: 'conn_rate'},
                    {data: 'pdd', name: 'pdd'},
                ],
                "order": [[ 2, "desc" ]]
            });

            // Refilter the table
            $('#min, #max, #country, #company').on('change', function () {
                table.draw();
            });
            
        });
    </script>
@endsection