<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Advanced Search :: DMS</title>
@include("admin.include.header")

<div class="main-content">
@include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Advanced Search</h2>
    <div class="adv-search">
      <div class="row mb-3">
        <div class="col-md-3">
          <label>Invoice Number</label>
          <input type="text" class="form-control" name="">
        </div>
        <div class="col-md-3">
          <label>Invoice Date</label>
          <input type="text" class="form-control" name="">
        </div>
        <div class="col-md-3">
          <label>Shipping Bill Number</label>
          <input type="text" class="form-control" name="">
        </div>
        <div class="col-md-3">
          <label>Sales Order Number</label>
          <input type="text" class="form-control" name="">
        </div>
      </div>
      <h5 class="sub-heading border-0">Document uploaded date range</h5>
      <div class="row">
        <div class="col-md-3">
          <label>From Date</label>
          <input type="text" class="form-control" name="">
        </div>
        <div class="col-md-3">
          <label>To Date</label>
          <input type="text" class="form-control" name="">
        </div>
        <div class="col-md-3">
          <input type="submit" value="Search" class="btn btn-primary btn-md btn-search" name="">
        </div>
      </div>
    </div>    
  </div>
</div>

@include("admin.include.footer")
