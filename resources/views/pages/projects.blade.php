@extends('layouts.app')

@section('title', 'Projects')

@section('topnavbar')
    <?php if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->is_admin) {?>
    @include('partials.adminnavbar')
    <?php } else { ?>
    @include('partials.navbar', ['notifications' => $notifications])
    <?php } ?>
@endsection

@section('content')

<div class="row m-0">
    <div class="col-md mt-5">
        <div class="container text-center my-3">
            <h2>Projects</h2>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Projects
                    <div class="input-group rounded w-50">
                        <input id="mySearch" type="search" name="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                        <button type="button" class="input-group-text border-0" id="search-addon" disabled>
                            <i class="icon-magnifier"></i>
                        </button>
                    </div>
                    <a href="projectsCreate" class="btn btn-outline-success" style="border-style:hidden;"><i class="icon-plus"></i> New Project</a>
                </div>
                <div class="card-header d-flex align-items-center">
                        <input type="checkbox" id="projects" name="projects" style="margin-right:10px">My Projects
                </div>
                <div id="myCardBody" class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-success">
                            <tr>
                                <th scope="col" class="text-center" style="width: 5%"><i class="icon-arrow-right-circle"></i></th>
                                <th scope="col">Project</th>
                                <th scope="col" style="width: 55%">Description</th>
                            </tr>
                        </thead>
                        <tbody id="table-projects-body">
                            <?php
                            $count = 1;
                            foreach ($my_projects as $project) {
                                echo '<tr>';
                                echo '<th scope="row" class="text-center"><a class="text-info my-rocket" href="/project/' . $project['id'] . '"><i class="icon-rocket"></i></a></th>';
                                echo '<td>' . $project['title'] . '</td>';
                                echo '<td>' . $project['description'] . '</td>';
                                echo '</tr>';
                                $count++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $my_projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    const mysearch = document.getElementById("mySearch");
    mysearch.addEventListener("keyup", searchProject);
    const mycheckbox = document.getElementById("projects");
    mycheckbox.addEventListener("change", searchProject);
    function searchProject() {
        let myfilter = document.getElementById("projects");
        sendAjaxRequest('post', '/api/myProjectsSearch', {search: mysearch.value, myprojects: myfilter.checked}, mySearchHandler);
    }
    function mySearchHandler() {
        //if(this.status != 200) window.location = '/';
        let projects = JSON.parse(this.responseText);
        let body = document.getElementById("table-projects-body");

        let paginations = document.getElementsByClassName('pagination');
        for (let pag of paginations) {
            if (document.getElementById('myCardBody').contains(pag)) {
                if (mysearch.value !== "") {
                    pag.style.display = 'none';
                } else {
                    if (projects.length > 10)
                        pag.style.display = 'flex';
                }
            }
        }

        body.innerHTML = "";
        let count = 0;
        for(let project of projects) {
            if (count === 10) break;
            count++;
            let tr = body.insertRow();
            let link = tr.insertCell();
            link.classList.add('text-center');
            link.innerHTML = '<a class="text-info my-rocket" href="/project/' + project['id'] + '"><i class="icon-rocket"><\/i><\/a>';
            let title = tr.insertCell();
            title.innerHTML = project['title'];
            let description = tr.insertCell();
            description.innerHTML = project['description'];
        }
    }
    </script>
@endsection
