@extends('layouts.app')

@section('title', 'Projects')

@section('topnavbar')
<?php if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->is_admin) { ?>
    @include('partials.adminnavbar')
<?php } else { ?>
    @include('partials.navbar', ['notifications' => $notifications])
<?php } ?>
@endsection

@section('content')
<div class="row m-0">
    <div class="col-sm-2">
        @include('partials.project_nav', ['project' => $project, 'user_role' => $user_role])
    </div>
    <div class="col-sm-8">
        <div class="d-flex gap-4 mt-5 container align-items-center text-uppercase">
            <h3><a class="text-decoration-none text-success" href="/project/{{$project->id}}">{{$project->title}}</a> / Files</h3>
        </div>
        <div class="col-md-12 px-4 my-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <?php if ($user_role != 'GUEST') { ?>
                        <form action="/project/{{$project->id}}/files/upload-files" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group my-3">
                                <input name="input_files[]" class="form-control" type="file" id="formFile" multiple required>
                                <button class="btn btn-outline-success" type="submit" id="formFile">Upload</button>
                            </div>
                        </form>
                    <?php } ?>
                    <a href="/project/{{$project->id}}/files/downloadZIP" class="btn btn-outline-success" style="border-style:hidden;"><i class="icon-folder-alt"></i> {!! "&nbsp;" !!} Download ZIP</a>
                </div>
                <div class="card-header d-flex align-items-center">

                    <div>
                        <span class="text-center text-danger"><i class="icon-shield"></i> Warning:{!! "&nbsp;" !!}</span>
                        Uploading files with a filename that already exists will replace the existing file
                    </div>
                </div>

                <div id="publicCardBody" class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-success">
                            <tr>
                                <th scope="col" style="width: 5%">Type</th>
                                <th scope="col">File Name</th>
                                <th scope="col" class="text-center" style="width: 15%">Upload Date</th>
                                <?php if ($user_role != 'GUEST') { ?>
                                    <th scope="col" class="text-center" style="width: 10%">Delete</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            foreach ($files as $file) {
                                echo '<tr>';
                                echo '<td class="text-center text-primary"><i class="icon-doc"></i></td>';
                                echo '<td><a href="/project/' . $project['id'] . '/files/' . $file->id . '/download" class="text-primary" style="text-decoration: none;">' . $file['name'] . '</a></td>';
                                echo '<td class="text-center">' . $file['updated_at'] . '</td>';
                                if ($user_role != 'GUEST') {
                                    echo '<td class="text-center"><a class="btn btn-outline-danger" href="/project/' . $project['id'] . '/files/' . $file->id . '/delete"><i class="icon-trash"></i></a></td>';
                                }
                                echo '</tr>';
                                $count++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!--script>
        document.getElementById("formFolder").addEventListener("change", function(event) {
            console.log(document.getElementById("formFolder").value);
            let files = event.target.files;
            for (let i=0; i<files.length; i++) {
                console.log(files[i].webkitRelativePath);
            }
            document.getElementById("dir_name").value = files;
        }, false);
    </script-->

@endsection
