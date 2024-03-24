<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Edit file :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Edit file</h2>
    <div class="tag-block">
      <form method="POST" action="{{ url('document_update') }}">
      <input type="hidden" name="id" value="{{$doc->id}}">
        @csrf   
          <table class="table table-striped"> 
          @if($doc->document_type=="Invoice")       
            <tr>
              <td>Invoice Number</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="invoice_number" value="{{$doc->invoice_number}}">
              </td>
            </tr>
            <tr>
              <td>Invoice Date</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="invoice_date" value="{{$doc->invoice_date}}">
              </td>
            </tr>
            @endif
            @if($doc->document_type=="Sales Order")
            <tr>
              <td>Sales Order Number</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="sales_order_number" value="{{$doc->sales_order_number}}" required>
              </td>
            </tr>
            @endif
            @if($doc->document_type=="Shipping Bill")
            <tr>
              <td>Shipping Bill Number :</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="shipping_bill_number" value="{{$doc->shipping_bill_number}}" required>
              </td>
            </tr>
            @endif
            <tr>
              <td>Company Name</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="company_name" value="{{$doc->company_name}}" required>
              </td>
            </tr>
            <tr>
              <td>Company ID</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="company_id" value="{{$doc->company_id}}" required>
              </td>
            </tr>
            <tr>
              <td>File name</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="filename" value="{{$doc->filename}}" disabled="">
              </td>
            </tr>
            <tr>
              <td>Uploaded date</td>
              <td width="10">:</td>
              <td>
                <input type="text" class="form-control" name="date" value="{{$doc->date}}" disabled="">
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
