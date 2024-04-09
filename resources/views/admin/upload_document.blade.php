<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.12.313/pdf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<title>Upload Docmuments :: DMS</title>
@include("admin.include.header")

<div class="main-content">
	@include("admin.include.menu_left")
	<div class="main-area">
		<h2 class="main-heading">Upload Docmument</h2>  
		<div class="dash-all pt-0">
			<div class="dash-table-all">        
				<table class="table" style="max-width: 600px;">
					<form method="POST" action="{{ url('submit_docs') }}" enctype="multipart/form-data">
						@csrf
						<tr>
							<td>Date</td>
							<td>{{\Carbon\Carbon::now()->setTimezone("Asia/Kolkata")->format('d-m-Y H:i:s A')}}</td>
						</tr>
						<tr>
							<td>Document type <span style="color: red">*</span></td>
							<td>
								<select class="form-control @error('doc_type') is-invalid @enderror" name="doc_type" id="doc_type" > 
									<option>Select</option>
									<option value="Invoice">Invoice</option>
									<option value="Sales Order">Sales Order</option>
									<option value="Shipping Bill">Shipping Bill</option>
								</select>
								 @error('doc_type')
									<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
									</span>
								@enderror
							</td>
						</tr>
						<tr>
							<td>Company ID <span style="color: red">*</span></td>
							<td><input type="text" class="form-control  @error('company_id') is-invalid @enderror" name="company_id" id="company_id" value="{{ old('company_id') }}" autocomplete="off">
							@error('company_id')
						<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
						</span>
					@enderror </td>
						</tr>
						<tr>
							<td>Company name <span style="color: red">*</span></td>
							<td><input type="text" class="form-control  @error('company_name') is-invalid @enderror" name="company_name" id="company_name" value="{{ old('company_name') }}"  autocomplete="off">
							@error('company_name')
						<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
						</span>
					@enderror </td>
						</tr>
						<tr>
							<td>Upload file<span style="color: red">*</span></td>
							<td>
									<input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror"  onchange="Checksize_image()">
										@error('image')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
										@enderror
							<div id="thumbnailContainer"></div> 
							<img src="" alt="Image Preview" id="preview" style="display: none;">
							 <!--  <span class="text-success">IN100200300.pdf</span> -->
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<button type="submit" class="btn btn-success" id="upload_document">Upload Document</button>
						</tr>
				</form>
				</table>
				
			</div>
		</div>
	</div>
</div>

<script>
		// document.getElementById('image').addEventListener('change', function(event) {
		//     var file = event.target.files[0];
		//     var reader = new FileReader();
				
		//     reader.onload = function(e) {
		//         var img = document.getElementById('preview');
		//         img.src = e.target.result;
		//         img.style.display = 'block';
		//     };
				
		//     reader.readAsDataURL(file);
		// });
</script>
<script>
// $(document).ready(function(){
// $('#image').on('change', function(event){
//     var file = event.target.files[0];
//     var fileReader = new FileReader();
		
//     fileReader.onload = function(){
//         var typedarray = new Uint8Array(this.result);
//         renderPDF(typedarray,file.name);
//     };
		
//     fileReader.readAsArrayBuffer(file);
// });
		
function renderPDF(data,fileName)
{
 return pdfjsLib.getDocument(data).promise.then(function(pdf)
 {
			return pdf.getPage(1).then(function(page)
			{
				var canvas = document.createElement('canvas');
				var context = canvas.getContext('2d');
				
				var viewport = page.getViewport({scale: 1.0});
				canvas.width = viewport.width;
				canvas.height = viewport.height;
				
				var renderContext = {
						canvasContext: context,
						viewport: viewport
				};
					
				return page.render(renderContext).promise.then(function() {
						var thumbnail = document.createElement('img');
						thumbnail.src = canvas.toDataURL();
						thumbnail.width = 100; // Adjust thumbnail size as needed
						thumbnail.height = thumbnail.width * (viewport.height / viewport.width);
						
						$('#thumbnailContainer').empty().append(thumbnail);
					function dataURItoBlob(dataURI){
						var binary=atob(dataURI.split(',')[1]);
						var array=[];
						for(i=0;i<binary.length;i++){
						array.push(binary.charCodeAt(i));
						}
						return new Blob([new Uint8Array(array)],{type:'image/png'});
					}
					let blob=(dataURItoBlob(canvas.toDataURL()));
					fileData=new File([blob],`${fileName}.png`,{type:"image/png",lastModified:new Date().getTime()});
					return fileData
					});
			});
 });
}
//});
</script>
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
<script type="text/javascript">
Checksize_image = () =>
{
	let formdata = new FormData();
	var docType = document.getElementById("doc_type").value;
	var companyId = document.getElementById("company_id").value;
	var companyName = document.getElementById("company_name").value;
	var image = document.getElementById("image").value;
	const fileInput = document.getElementById('image');
	const fileList = fileInput.files;
	const file = fileList[0];

	var myfiledata 
	formdata.append("doc_type", docType);
	formdata.append("company_id", companyId);
	formdata.append("company_name", companyName);
	formdata.append('document_file', file);
	var allowedExtensions = /(\.pdf)$/i;
	if(!allowedExtensions.exec(image))
	{
		$("#upload_document").attr("disabled", true);
		var msg="Please upload file having extensions .pdf only.";
		formdata.append("msg", msg);
		$.ajax({
			url: '{{url("/failed_docs")}}',
			method: 'POST',
			headers:
			{
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			processData: false,
			contentType: false,
			data:formdata,
		success: function(newData)
		{
			if(newData.success == 1){  
				swal({
					title: "Error!",
					text: newData.message,
					icon: "error",
				});
			 setTimeout(function()
				{
					window.location.href="{{url("upload_document")}}";
				}, 3000);
			} 
		},
		error: function(xhr, status, error){
				console.error(error);
		}
		}); 
	}
	else
	{
		var fileReader = new FileReader();
	 fileReader.onload = async function(){
			var typedarray = new Uint8Array(this.result);
			myfiledata = await renderPDF(typedarray,(file.name).split('.').slice(0, -1).join('.'));
		formdata.append('thumbnail', myfiledata);

		const fsize = file.size;
			const filesize = Math.round((fsize / 1024));
			var msg="";
			if (filesize >= 2048)
			{
					$("#upload_document").attr("disabled", true);
					msg="File too Big, please select a file less than 2mb.";
					formdata.append("msg", msg);
					$.ajax({
						url: '{{url("/failed_docs")}}',
						method: 'POST',
						headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						processData: false,
						contentType: false,
						data: formdata,
						success: function(newData) {
							if(newData.success == 1){  
								swal({
									title: "Error!",
									text: newData.message,
									icon: "error",
								});
								setTimeout(function()
								{
									window.location.href="{{url("upload_document")}}";
								}, 3000);
							} 
						},
						error: function(xhr, status, error){
								console.error(error);
						}
					}); 
			}
			else if(filesize < 15)
			{
					$("#upload_document").attr("disabled", true);
					msg="File too small, please select a file greater than 10kb.";
					formdata.append("msg", msg);
					$.ajax({
						url: '{{url("/failed_docs")}}',
						method: 'POST',
						headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						processData: false,
						contentType: false,
						data: formdata,
						success: function(newData) {
							if(newData.success == 1){  
								swal({
									title: "Error!",
									text: newData.message,
									icon: "error",
								});
								setTimeout(function()
								{
									window.location.href="{{url("upload_document")}}";
								}, 3000);
							} 
						},
						error: function(xhr, status, error){
								console.error(error);
						}
					}); 
			}
			else
			{
				$.ajax({
					url: '{{url("/pdf_to_thubnail_docs")}}',
					method: 'POST',
					headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					processData: false,
					contentType: false,
					data: formdata,
					success: function(newData) 
					{
						// if(newData.success == 1){  
						//   swal({
						//     title: "Error!",
						//     text: newData.message,
						//     icon: "error",
						//   });
						//   setTimeout(function()
						//   {
						//     window.location.href="{{url("upload_document")}}";
						//   }, 3000);
						// } 
					},
					error: function(xhr, status, error){
							console.error(error);
					}
				});  
			}
	 };
		
		fileReader.readAsArrayBuffer(file);
	}
}

</script>
@include("admin.include.footer")
