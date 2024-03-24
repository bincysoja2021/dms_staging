<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>DMS :: GTN Textiles</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Dashboard</h2>
     @if(Session::has('message'))
        <div class="alert alert-{{session('message')['type']}}">
            {{session('message')['text']}}
        </div>
      @endif
    @include("admin.include.search")
    <div class="dashbox-cover">
      <div class="dashbox-in">
        <div class="dashbox"> 
          <img src="{{ asset ('images/document-icon.svg') }}">
          <h4>4520</h4>
        </div>
        <h3>All Documents</h3>
      </div>
      <div class="dashbox-in">
        <div class="dashbox"> 
          <img src="{{ asset ('images/invoice-icon.svg') }}">
          <h4>2700</h4>
        </div>
        <h3>Invoices</h3>
      </div>
      <div class="dashbox-in">
        <div class="dashbox"> 
          <img src="{{ asset ('images/sales-order-icon.svg') }}">
          <h4>700</h4>
        </div>
        <h3>Sales Orders</h3>
      </div>
      <div class="dashbox-in">
        <div class="dashbox"> 
          <img src="{{ asset ('images/shipping-bill-icon.svg') }}">
          <h4>2100</h4>
        </div>
        <h3>Shipping Bills</h3>
      </div>
      <div class="dashbox-in">
        <div class="dashbox"> 
          <img src="{{ asset ('images/notification-icon.svg') }}">
          <h4>{{$notification}}</h4>
        </div>
        <h3>Notifications</h3>
      </div>
    </div>
    <div class="dash-other">
      <div class="row">
        <div class="col-md-6">
          <div class="dash-home">
            <h4 class="sub-heading">Recent Uploaded Documents</h4>
            <table class="table table-striped">
              <thead>
                <th>Sl.</th>
                <th>Document ID</th>
                <th>Date</th>
                <th>Status</th>
              </thead>
              <tbody>
                <tr>
                  <td>01.</td>
                  <td>SO-4512012</td>
                  <td>20-02-2024</td>
                  <td><span class="text-success">Success</span></td>
                </tr>
                <tr>
                  <td>02.</td>
                  <td>SO-14588</td>
                  <td>13-01-2024</td>
                  <td><span class="text-success">Success</span></td>
                </tr>
                <tr>
                  <td>03.</td>
                  <td>SO-645896</td>
                  <td>18-07-2023</td>
                  <td><span class="text-success">Success</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-md-6">
          <div class="dash-home">
            <h4 class="sub-heading">Failed Uploads</h4>
            <table class="table table-striped">
              <thead>
                <th>Sl.</th>
                <th>Document ID</th>
                <th>Date</th>
                <th>Status</th>
              </thead>
              <tbody>
                <tr>
                  <td>01.</td>
                  <td>SO-4512012</td>
                  <td>20-02-2024</td>
                  <td><span class="text-danger">Failed</span></td>
                </tr>
                <tr>
                  <td>02.</td>
                  <td>SO-14588</td>
                  <td>13-01-2024</td>
                  <td><span class="text-danger">Failed</span></td>
                </tr>
                <tr>
                  <td>03.</td>
                  <td>SO-645896</td>
                  <td>18-07-2023</td>
                  <td><span class="text-danger">Failed</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@include("admin.include.footer");?>
