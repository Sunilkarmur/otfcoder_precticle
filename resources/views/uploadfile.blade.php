@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">Upload File Example</div>

                <div class="card-body">
                    <a class="btn btn-info" href="{{ route('home') }}">Back</a>
                    @if ($message = Session::get('success'))

                        <div class="alert alert-success alert-block">

                            <button type="button" class="close" data-dismiss="alert">×</button>

                            <strong>{{ $message }}</strong>

                        </div>

                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <form action="{{ route("pdf.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Name/Description fields, irrelevant for this article --}}

                            <div class="form-group">
                                <label for="document">Documents</label>
                                <div class="needsclick dropzone" id="document-dropzone">

                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
@endpush
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script>
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: '{{ route('pdf.store') }}',
            paramName: 'fileToUpload',
            clickable: true,
            acceptedFiles: ".pdf",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            removedfile: function (file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="document[]"][value="' + name + '"]').remove()
            },
            init: function() {
                this.on("maxfilesexceeded", function(file){
                    var msgEl = $(file.previewElement).find('.dz-error-message');
                    msgEl.text('Only one file allowed');
                    msgEl.show();
                    msgEl.css("opacity", 1);
                    return false
                });
                this.on("success", function(file, responseText) {
                    alert(responseText.message)
                });
            },
            error:function (file, error) {
                if (file && error) {
                    var msgEl = $(file.previewElement).find('.dz-error-message');
                    msgEl.text(error.message?error.message:error);
                    msgEl.show();
                    msgEl.css("opacity", 1);
                }else {
                    var msgEl = $(file.previewElement).find('.dz-error-message');
                    msgEl.text(error);
                    msgEl.show();
                    msgEl.css("opacity", 1);
                }
            },
            success:function (file, response) {
                console.log(response.message)
                var msgEl = $(file.previewElement).find('.dz-success-message');
                msgEl.text(response.message);
                msgEl.show();
                msgEl.css("opacity", 1);
                // if (file && error) {
                //     var msgEl = $(file.previewElement).find('.dz-error-message');
                //     msgEl.text(error.message?error.message:error);
                //     msgEl.show();
                //     msgEl.css("opacity", 1);
                // }else {
                //     var msgEl = $(file.previewElement).find('.dz-error-message');
                //     msgEl.text(error);
                //     msgEl.show();
                //     msgEl.css("opacity", 1);
                // }
            }
        }

    </script>
@endpush
