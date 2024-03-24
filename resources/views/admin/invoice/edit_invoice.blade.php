<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Edit invoice :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Edit file</h2>
    <div class="tag-block">
      <form method="POST" action="{{ url('invoice_document_update') }}">
      <input type="hidden" name="id" value="{{$doc->id}}">
          @csrf 
          <table class="table table-striped">  
          <tr>
              <td>Document Type</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="document_type" value="{{$doc->document_type}}" readonly="">
              </td>
            </tr>      
            <tr>
              <td>Invoice Number</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="invoice_number" value="{{$doc->invoice_number}}" required="">
              </td>
            </tr>
            <tr>
              <td>Invoice Date</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="invoice_date" value="{{$doc->invoice_date}}"  readonly="">
              </td>
            </tr>
            <!-- <tr>
              <td>Sales Order Number</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="" value="SO100200300">
              </td>
            </tr>
            <tr>
              <td>Shipping Bill Number :</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="" value="SB100200300">
              </td>
            </tr> -->
            <tr>
              <td>Company Name</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="company_name" value="{{$doc->company_name}}" required="">
              </td>
            </tr>
            <tr>
              <td>Company ID</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="company_id" value="{{$doc->company_id}}" required="">
              </td>
            </tr>
            <tr>
              <td>File name</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="" value="{{$doc->filename}}" disabled="">
              </td>
            </tr>
            <tr>
              <td>Uploaded date</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="" value="{{$doc->date}}" disabled="">
              </td>
            </tr>        
          </table>
          <div class="btn-groups">        
            <button type="submit"  class="btn btn-primary">Update</button>
          </div>
    </form>
    </div>
  </div>
</div>
@include("admin.include.footer")
