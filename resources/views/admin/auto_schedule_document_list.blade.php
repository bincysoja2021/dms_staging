<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<style type="text/css">
  .fa-trash {
    color: white; /* Change to your desired color */
}
</style>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- styles and scripts -->
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<title>All Users :: DMS</title>
@include("admin.include.header")

<div class="main-content">
 @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">All Users</h2>  
    <div class="dash-all pt-0">
      <div class="dash-table-all">

        <div class="mt-4">
        <button id="showTableOne" class="btn btn-primary" type="button">
            Upcoming scheduled List
        </button>
        <button id="showTableTwo" class="btn btn-primary" type="button">
            Complte scheduled List
        </button>

        <div class="collapse mt-3" id="tableOne">
            <div class="card card-body">
                <table class="table table-striped active-datatable">
                    <thead>
                        <tr>
                            <th scope="col">SL.NO</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auto_schedule_document_active as $key=>$val)
                        <tr>
                            <th scope="row">{{$key + 1}}</th>
                            <td>{{$val->start_date}}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('H:i', $val->time)->format('h:i A') }}</td>
                            <td><span class="{{ $val->status == 'Active' ? 'text-success' : '' }}">{{$val->status}}</span></td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$auto_schedule_document_active->links('pagination::bootstrap-4')}}
            </div>
        </div>

        <div class="collapse mt-3" id="tableTwo">
            <div class="card card-body">
                <table class="table table-striped inactive-datatable">
                    <thead>
                        <tr>
                            <th scope="col">SL.NO</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auto_schedule_document_inactive as $key=>$val)
                        <tr>
                            <th scope="row">{{$key + 1}}</th>
                            <td>{{$val->start_date}}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('H:i', $val->time)->format('h:i A') }}</td>
                            <td><span class="{{ $val->status == 'Inactive' ? 'text-danger' : '' }}">{{$val->status}}</span></td>
                        </tr>
                        @endforeach
                        
                    </tbody>

                </table>
                    {{$auto_schedule_document_inactive->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>      










       
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
  @if(session()->has('message'))
      swal({

          title: "Success!",

          text: "{{ session()->get('message') }}",

          icon: "success",

      });
  @endif
  </script>
<script>
        document.getElementById('tableOne').classList.add('show');
        document.getElementById('tableTwo').classList.remove('show');

        document.getElementById('showTableOne').addEventListener('click', function () {
            document.getElementById('tableOne').classList.add('show');
            document.getElementById('tableTwo').classList.remove('show');
        });

        document.getElementById('showTableTwo').addEventListener('click', function () {
            document.getElementById('tableTwo').classList.add('show');
            document.getElementById('tableOne').classList.remove('show');
        });
    </script>
@include("admin.include.footer")
