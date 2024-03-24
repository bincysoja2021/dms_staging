<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Tags :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Tags</h2>
    <div class="tag-block">
      <table class="table table-striped">
        <tr>
          <td width="20"><input type="checkbox" class="checkbox"></td>          
          <td>Invoice Number</td>              
        </tr>
        <tr>
          <td width="20"><input type="checkbox" class="checkbox"></td>
          <td>Invoice Date</td>          
        </tr>
        <tr>
          <td width="20"><input type="checkbox" class="checkbox"></td>
          <td>Sales Order Number</td>          
        </tr>
        <tr>
          <td width="20"><input type="checkbox" class="checkbox"></td>
          <td>Shipping Bill Number</td>          
        </tr>
        <tr>
          <td width="20"><input type="checkbox" class="checkbox"></td>
          <td>Company Name</td>          
        </tr>
        <tr>
          <td width="20"><input type="checkbox" class="checkbox"></td>
          <td>Company ID</td>          
        </tr>
        <tr>
          <td width="20"><input type="checkbox" class="checkbox"></td>
          <td>File name</td>          
        </tr>
        <tr>
          <td width="20"><input type="checkbox" class="checkbox"></td>
          <td>Uploaded date</td>          
        </tr>        
      </table>
    </div>
  </div>
</div>

@include("admin.include/footer")
