@extends('faturhelper::layouts/admin/main')

@section('title', 'Edit Pengumuman')

@section('content')
<script src="https://cdn.tiny.cloud/1/nnd7pakaxqr7isf3oqefsdlew1jsidgl78umfeus6tg21ng0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0" style="text-transform: capitalize">Edit Pengumuman</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('pengumumanupdate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Judul*</label>
                            <input required placeholder="Judul" type="text" name="judul" id="judul" value="{{$data->judul}}" class="form-control"autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Expired*</label>
                            <input required type="date" name="expired" id="expired" class="form-control" value="{{$data->expired}}" autofocus>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Thumbnail</label><br>
                            @if ($data->thumbnail != '' && $data->thumbnail != null)
                                <div class="w-100 text-center">
                                    <img class="mb-2" src="{{ URL::asset('storage/'.$data->thumbnail) }}" height="60px" style="border: 1px solid black">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control" autofocus accept="image/*">
                            <small>*silahkan pilih file thumbnail jika ingin memperbaharui</small>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Isi Pengumuman</label>
                            <textarea name="isi" id="isi" class="form-control" rows="10">{!! $data->isi !!}`</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('pengumuman') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>
<script>

    tinymce.init({
        selector: 'textarea',

        image_class_list: [
        {title: 'img-responsive', value: 'img-responsive'},
        ],
        height: 500,
        setup: function (editor) {
            editor.on('init change', function () {
                editor.save();
            });
        },
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",

        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/master/pengumumanuploadimg',
        file_picker_types: 'image',
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                };
            };
            input.click();
        }
    });

</script>

@endsection
