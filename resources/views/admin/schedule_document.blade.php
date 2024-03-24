<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Schedule Documents :: DMS</title>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" />
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Schedule Documents</h2>
    <div class="schedule-sec">    
      <div class="row">
        <div class="col-md-8">
          <p>
            Please add the documents in the system folder D:\DMS\upload.<br/>
            If documents are added to the folder please click to <span class="text-danger">Start Schedule</span>.
          </p>
          <div class="input-group radio-cover">
            <span>
              <input type="radio" class="radio" name="manual" value="manual" onclick="showmanual(1);">
              Manual Schedule
            </span>
            <span>
              <input type="radio" class="radio" name="manual" value="pre_schedule" onclick="showmanual(2);">
              Pre-Schedule
            </span>            
          </div>
          <div id="div1" style="display:none;">
            <table class="table detail-table">
              <tr>
                <td>Date</td>
                <td><input type="text" name="date" class="form-control"></td>
              </tr>
              <tr>
                <td>Time</td>
                <td><input class="time standard" type="text" value="00:00" onchange="console.log('Time changed to: ' + this.value)" /></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
            </table>
            <a href="" class="btn btn-primary btn-lg">Start Schedule</a>
            <hr>
        </div>
        <div id="div2" style="display:none;">
              <h5>Schedule for :</h5>
              <div class="input-group radio-cover">
                <span>
                  <input type="radio" class="radio" name="manual">
                  7 days
                </span>
                <span>
                  <input type="radio" class="radio" name="manual">
                  15 days
                </span>
                <span>
                  <input type="radio" class="radio" name="manual">
                  30 days
                </span>            
              </div>
              <table class="table detail-table">
                <tr>
                  <td>Start Date</td>
                  <td><input type="text" name="date" class="form-control"></td>
                </tr>
                <tr>
                  <td>Time</td>
                  <td><input class="time standard" type="text" value="00:00" onchange="console.log('Time changed to: ' + this.value)" /></td>
                </tr>
                <tr>
                  <td></td>
                  <td></td>
                </tr>
              </table>
              <a href="" class="btn btn-primary btn-lg">Start Schedule</a>
        </div>
        </div>
        <div class="col-md-4">
          <h5>Steps to follow for schedule document upload</h5>
          <ul class="schedule-steps">
            <li>Scan the documents</li>
            <li>Save the documents as pdf</li>
            <li>Keep the document size less than 16 MB</li>
            <li>Give the document name in the mentioned format (Format : ---)</li>
            <li>Add the documents to the mentioned path (Path: ---)</li>
            <li></li>
            <li></li>
            <li></li>
          </ul>
        </div>        
      </div>
      
    </div>
  </div>
</div>
<script type="text/javascript">
  function showmanual(val)
  {
    if(val==1)
    {
     document.getElementById('div1').style.display ='block';
     document.getElementById('div2').style.display = 'none';
    }
    else if(val==2)
    {
      document.getElementById('div1').style.display ='none';
      document.getElementById('div2').style.display = 'block';
    }
  }
</script>

@include("admin.include.footer")
