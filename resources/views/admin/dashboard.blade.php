@extends('layouts.app.frame')
@section('title', 'Dashboard')
@section('description', 'Request Histories from Teacher must be confirmation')
@section('breadcrumbs')
	@php echo \Breadcrumbs::render(['Dashboard']) @endphp
@endsection

@section('content')
	<input type="hidden" id="drs" name="drange"/>
    <input type="hidden" id="did" name="did"/>
    <div class="form-group-attached">
        <div class="row clearfix">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group form-group-default">
                    <label>Pencarian</label>
                    <form id="formsearch-history">
                        <input type="text" id="search-history-table" class="form-control" name="firstName" placeholder="put your keyword">
                    </form>
                </div>
            </div>
            <div class="col-sm-3 col-xs-6">
                <div class="form-group form-group-default">
                    <label>Start date</label>
                    <input type="text" id="datepicker-start-history" class="form-control" name="firstName" placeholder="pick a start date">
                </div>
            </div>
            <div class="col-sm-3 col-xs-6">
                <div class="form-group form-group-default">
                    <label>End date</label>
                    <input type="text" id="datepicker-end-history" class="form-control" name="firstName" placeholder="pick an end date">
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <table class="table table-hover" id="history-table">
        <thead>
            <tr>
                <th>Unique Number</th>
                <th>Teacher</th>
				<th>Private</th>
				<th>Total</th>
				<th>Status</th>
				<th>Created At</th>
				<th width="14%"> Actions </th>
            </tr>
        </thead>
    </table>
	
	<br/>
	<br/>
	<h4>Teachers must be confirmation</h4>
    <input type="hidden" id="drs" name="drange"/>
    <input type="hidden" id="did" name="did"/>
    <div class="form-group-attached">
        <div class="row clearfix">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group form-group-default">
                    <label>Pencarian</label>
                    <form id="formsearch">
                        <input type="text" id="search-table" class="form-control" name="firstName" placeholder="put your keyword">
                    </form>
                </div>
            </div>
            <div class="col-sm-3 col-xs-6">
                <div class="form-group form-group-default">
                    <label>Start date</label>
                    <input type="text" id="datepicker-start" class="form-control" name="firstName" placeholder="pick a start date">
                </div>
            </div>
            <div class="col-sm-3 col-xs-6">
                <div class="form-group form-group-default">
                    <label>End date</label>
                    <input type="text" id="datepicker-end" class="form-control" name="firstName" placeholder="pick an end date">
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <table class="table table-hover" id="user-table">
        <thead>
            <tr>
                <th>Unique Number</th>
				<th>Title</th>
				<th>Name</th>
				<th>Email</th>
				<th>Phone Number</th>
				<th>Graduated</th>
				<th>Address</th>
				<th>Last Login At</th>
				<th>Created At</th>
				<th width="14%"> Actions </th>
            </tr>
        </thead>
    </table>
	
	<br/>
	<br/>
	<h4>Teachers Course must be Confirmations</h4>s
    <input type="hidden" id="drs" name="drange"/>
    <input type="hidden" id="did" name="did"/>
    <div class="form-group-attached">
        <div class="row clearfix">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group form-group-default">
                    <label>Pencarian</label>
                    <form id="formsearchconfirmation">
                        <input type="text" id="search-confirmation-table" class="form-control" name="firstName" placeholder="put your keyword">
                    </form>
                </div>
            </div>
            <div class="col-sm-3 col-xs-6">
                <div class="form-group form-group-default">
                    <label>Start date</label>
                    <input type="text" id="datepicker-start-confirmation" class="form-control" name="firstName" placeholder="pick a start date">
                </div>
            </div>
            <div class="col-sm-3 col-xs-6">
                <div class="form-group form-group-default">
                    <label>End date</label>
                    <input type="text" id="datepicker-end-confirmation" class="form-control" name="firstName" placeholder="pick an end date">
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <table class="table table-hover" id="course-table">
        <thead>
            <tr>
                <th>Unique Number</th>
				<th>Name</th>
				<th>Email</th>
				<th>Course</th>
				<th>Expected Cost</th>
				<th>Total Cost</th>
				<th>Created At</th>
				<th width="14%"> Actions </th>
            </tr>
        </thead>
    </table>

@endsection

@push("script")
<script>

var oTable;
oTable = $('#user-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'lBfrtip',
    order:  [[ 8, "desc" ]],
    buttons: [
        {
            extend: 'print',
            autoPrint: true,
            customize: function ( win ) {
                $(win.document.body)
                    .css( 'padding', '2px' )
                    .prepend(
                        '<img src="{{asset('img/logo.png')}}" style="float:right; top:0; left:0;height: 40px;right: 10px;background: #101010;padding: 8px;border-radius: 4px" /><h5 style="font-size: 9px;margin-top: 0px;"><br/><font style="font-size:14px;margin-top: 5px;margin-bottom:20px;"> Order Report</font><br/><br/><font style="font-size:8px;margin-top:15px;">{{date('Y-m-d h:i:s')}}</font></h5><br/><br/>'
                    );


                $(win.document.body).find( 'div' )
                    .css( {'padding': '2px', 'text-align': 'center', 'margin-top': '-50px'} )
                    .prepend(
                        ''
                    );

                $(win.document.body).find( 'table' )
                    .addClass( 'compact' )
                    .css( { 'font-size': '9px', 'padding': '2px' } );


            },
            title: '',
            orientation: 'landscape',
            exportOptions: {columns: ':visible'} ,
            text: '<i class="fa fa-print" data-toggle="tooltip" title="" data-original-title="Print"></i>'
        },
        {extend: 'colvis', text: '<i class="fa fa-eye" data-toggle="tooltip" title="" data-original-title="Column visible"></i>'},
        {extend: 'csv', text: '<i class="fa fa-file-excel-o" data-toggle="tooltip" title="" data-original-title="Export CSV"></i>'}
    ],
    sDom: "<'table-responsive fixed't><'row'<p i>> B",
    sPaginationType: "bootstrap",
    destroy: true,
    responsive: true,
    scrollCollapse: true,
    oLanguage: {
        "sLengthMenu": "_MENU_ ",
        "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
    },
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    ajax: {
		url: '{!! route('dashboard.listteacher') !!}',
        data: function (d) {
            d.range = $('input[name=drange]').val();
        }
    },
    columns: [
		{ data: "unique_number", name: "unique_number" },
		{ data: "title", name: "title" },
		{ data: "first_name", name: "first_name" },
		{ data: "email", name: "email", visible:false  },
		{ data: "phone_number", name: "phone_number", visible:false },
		{ data: "graduated", name: "graduated", visible:false },
		{ data: "address", name: "address", visible:false },
		{ data: "last_login_at", name: "last_login_at" },
		{ data: "created_at", name: "created_at" },
		{ data: "action", name: "action", searchable: false, orderable: false }
    ],
}).on( 'processing.dt', function ( e, settings, processing ) {if(processing){Pace.start();} else {Pace.stop();}});

//$("#user-table_wrapper > .dt-buttons").appendTo("div.export-options-container");


$('#datepicker-start').datepicker({format: 'yyyy/mm/dd'}).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    if($('#datepicker-end').val() != ""){
        $('#drs').val($('#datepicker-start').val()+":"+$('#datepicker-end').val());
        oTable.draw();
    }else{
        $('#datepicker-end').focus();
    }

});
$('#datepicker-end').datepicker({format: 'yyyy/mm/dd'}).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    if($('#datepicker-start').val() != ""){
        $('#drs').val($('#datepicker-start').val()+":"+$('#datepicker-end').val());
        oTable.draw();
    }else{
        $('#datepicker-start').focus();
    }

});

$('#formsearch').submit(function () {
    oTable.search( $('#search-table').val() ).draw();
    return false;
} );

oTable.page.len(25).draw();



function deleteData(id) {
    $('#modalDelete').modal('show');
    $('#did').val(id);
}

function hapus(){
    $('#modalDelete').modal('hide');
    var id = $('#did').val();
    $.ajax({
        url: '{{url("admin/order")}}' + "/" + id + '?' + $.param({"_token" : '{{ csrf_token() }}' }),
        type: 'DELETE',
        complete: function(data) {
            oTable.draw();
        }
    });
}

var oTable1;
oTable1 = $('#history-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'lBfrtip',
    order:  [[ 0, "desc" ]],
    buttons: [
        {
            extend: 'print',
            autoPrint: true,
            customize: function ( win ) {
                $(win.document.body)
                    .css( 'padding', '2px' )
                    .prepend(
                        '<img src="{{asset('img/logo.png')}}" style="float:right; top:0; left:0;height: 40px;right: 10px;background: #101010;padding: 8px;border-radius: 4px" /><h5 style="font-size: 9px;margin-top: 0px;"><br/><font style="font-size:14px;margin-top: 5px;margin-bottom:20px;"> Order Report</font><br/><br/><font style="font-size:8px;margin-top:15px;">{{date('Y-m-d h:i:s')}}</font></h5><br/><br/>'
                    );


                $(win.document.body).find( 'div' )
                    .css( {'padding': '2px', 'text-align': 'center', 'margin-top': '-50px'} )
                    .prepend(
                        ''
                    );

                $(win.document.body).find( 'table' )
                    .addClass( 'compact' )
                    .css( { 'font-size': '9px', 'padding': '2px' } );


            },
            title: '',
            orientation: 'landscape',
            exportOptions: {columns: ':visible'} ,
            text: '<i class="fa fa-print" data-toggle="tooltip" title="" data-original-title="Print"></i>'
        },
        {extend: 'colvis', text: '<i class="fa fa-eye" data-toggle="tooltip" title="" data-original-title="Column visible"></i>'},
        {extend: 'csv', text: '<i class="fa fa-file-excel-o" data-toggle="tooltip" title="" data-original-title="Export CSV"></i>'}
    ],
    sDom: "<'table-responsive fixed't><'row'<p i>> B",
    sPaginationType: "bootstrap",
    destroy: true,
    responsive: true,
    scrollCollapse: true,
    oLanguage: {
        "sLengthMenu": "_MENU_ ",
        "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
    },
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    ajax: {
		url: '{!! route('dashboard.listotalhistories') !!}',
        data: function (d) {
            d.range = $('input[name=drange]').val();
        }
    },
    columns: [
		{ data: "unique_number", name: "unique_number" },
		{ data: "first_name", name: "first_name" },
		{ data: "private_id", name: "private_id" },
		{ data: "total", name: "total" },
		{ data: "status", name: "status" },
		{ data: "created_at", name: "created_at" },
		{ data: "action", name: "action", searchable: false, orderable: false }
    ],
}).on( 'processing.dt', function ( e, settings, processing ) {if(processing){Pace.start();} else {Pace.stop();}});

//$("#history-table_wrapper > .dt-buttons").appendTo("div.export-options-container");


$('#datepicker-start-history').datepicker({format: 'yyyy/mm/dd'}).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    if($('#datepicker-end-history').val() != ""){
        $('#drs').val($('#datepicker-start-history').val()+":"+$('#datepicker-end-history').val());
        oTable.draw();
    }else{
        $('#datepicker-end-history').focus();
    }

});
$('#datepicker-end-history').datepicker({format: 'yyyy/mm/dd'}).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    if($('#datepicker-start-history').val() != ""){
        $('#drs').val($('#datepicker-start-history').val()+":"+$('#datepicker-end-history').val());
        oTable.draw();
    }else{
        $('#datepicker-start-history').focus();
    }

});

$('#formsearch-history').submit(function () {
    oTable.search( $('#search-history-table').val() ).draw();
    return false;
} );

oTable1.page.len(25).draw();

var oTable2;
oTable2 = $('#course-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'lBfrtip',
    order:  [[ 6, "desc" ]],
    buttons: [
        {
            extend: 'print',
            autoPrint: true,
            customize: function ( win ) {
                $(win.document.body)
                    .css( 'padding', '2px' )
                    .prepend(
                        '<img src="{{asset('img/logo.png')}}" style="float:right; top:0; left:0;height: 40px;right: 10px;background: #101010;padding: 8px;border-radius: 4px" /><h5 style="font-size: 9px;margin-top: 0px;"><br/><font style="font-size:14px;margin-top: 5px;margin-bottom:20px;"> Order Report</font><br/><br/><font style="font-size:8px;margin-top:15px;">{{date('Y-m-d h:i:s')}}</font></h5><br/><br/>'
                    );


                $(win.document.body).find( 'div' )
                    .css( {'padding': '2px', 'text-align': 'center', 'margin-top': '-50px'} )
                    .prepend(
                        ''
                    );

                $(win.document.body).find( 'table' )
                    .addClass( 'compact' )
                    .css( { 'font-size': '9px', 'padding': '2px' } );


            },
            title: '',
            orientation: 'landscape',
            exportOptions: {columns: ':visible'} ,
            text: '<i class="fa fa-print" data-toggle="tooltip" title="" data-original-title="Print"></i>'
        },
        {extend: 'colvis', text: '<i class="fa fa-eye" data-toggle="tooltip" title="" data-original-title="Column visible"></i>'},
        {extend: 'csv', text: '<i class="fa fa-file-excel-o" data-toggle="tooltip" title="" data-original-title="Export CSV"></i>'}
    ],
    sDom: "<'table-responsive fixed't><'row'<p i>> B",
    sPaginationType: "bootstrap",
    destroy: true,
    responsive: true,
    scrollCollapse: true,
    oLanguage: {
        "sLengthMenu": "_MENU_ ",
        "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
    },
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    ajax: {
		url: '{!! route('dashboard.listTeacherCourseConfirmations') !!}',
        data: function (d) {
            d.range = $('input[name=drange]').val();
        }
    },
    columns: [
		{ data: "unique_number", name: "unique_number" },
		{ data: "first_name", name: "first_name" },
		{ data: "email", name: "email" },
		{ data: "course.name", name: "course.name" },
		{ data: "expected_cost", name: "expected_cost" },
		{ data: "final_cost", name: "final_cost" },
		{ data: "created_at", name: "created_at" },
		{ data: "action", name: "action", searchable: false, orderable: false }
    ],
}).on( 'processing.dt', function ( e, settings, processing ) {if(processing){Pace.start();} else {Pace.stop();}});

//$("#history-table_wrapper > .dt-buttons").appendTo("div.export-options-container");


$('#datepicker-start-confirmation').datepicker({format: 'yyyy/mm/dd'}).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    if($('#datepicker-end-confirmation').val() != ""){
        $('#drs').val($('#datepicker-start-confirmation').val()+":"+$('#datepicker-end-confirmation').val());
        oTable.draw();
    }else{
        $('#datepicker-end-confirmation').focus();
    }

});
$('#datepicker-end-confirmation').datepicker({format: 'yyyy/mm/dd'}).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    if($('#datepicker-start-confirmation').val() != ""){
        $('#drs').val($('#datepicker-start-confirmation').val()+":"+$('#datepicker-end-confirmation').val());
        oTable.draw();
    }else{
        $('#datepicker-start-confirmation').focus();
    }

});

$('#formsearchconfirmation').submit(function () {
    oTable.search( $('#search-confirmation-table').val() ).draw();
    return false;
} );

oTable2.page.len(25).draw();

</script>
@endpush