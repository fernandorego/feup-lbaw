@extends('layouts.app')

@section('topnavbar')
    <?php if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->is_admin) {?>
    @include('partials.adminnavbar')
    <?php } else { ?>
    @include('partials.navbar', ['notifications' => $notifications])
    <?php } ?>
@endsection

@section('title', 'Tasks')

@section('content')

<div class="col-2">
    @include('partials.project_nav', ['project' => $project,  'user_role' => $user_role])
        </div>
        <!-- <div class="container text-center my-3" style="margin-top:4%;">
                <h2>{{$project->title}}</h2>
            </div> -->
        <div class="formbg-outer">
            <div class="formbg">
                <div class="formbg-inner" style="padding: 48px">
                    <span style="padding-bottom: 15px">Create a task</span>
                    <form method="POST" action='/tasksCreate'>
                        {{ csrf_field() }}
                        <div class="field" style="padding-bottom: 24px">
                            <label for="name">Name</label>
                            <input type="text" name="name" required>
                        </div>
                        @if ($errors->has('name'))
                        <div class="field">
                            <span class="error">
                                {{ $errors->first('name') }}
                            </span>
                        </div>
                        @endif
                        <div class="field" style="padding-bottom: 24px">
                            <label for="description">Description</label>
                            <input type="text" name="description" required>
                        </div>
                        @if ($errors->has('description'))
                        <div class="field">
                            <span class="error">
                                {{ $errors->first('description') }}
                            </span>
                        </div>
                        @endif
                        <div class="field" style="padding-bottom: 24px">
                            <label for="tag">Status</label>
                            <select name="tag" class="form-select" aria-label="Disabled select example" required>
                                <option value='TODO'>TODO</option>
                                <option value='DOING'>DOING</option>
                                <option value='REVIEW'>REVIEW</option>
                                <option value='CLOSED'>CLOSED</option>
                            </select>
                        </div>
                        <div class="field" style="padding-bottom: 24px">
                            <label for="tag">Assignee</label>
                            <select name="user_id" class="form-select" aria-label="Disabled select example">
                                <option selected value="-1"> </option>
                                @foreach ($users as $user)
                                        <option value="{{ $user['user_id'] }}">{{$user['username']}} </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('tag'))
                        <span class="error">
                            {{ $errors->first('tag') }}
                        </span>
                        @endif
                        <div class="field" style="padding-bottom: 24px">
                            <label for="due_date">Due Date</label>
                            <input class="date form-control" type="date" name="due_date" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                        </div>
                        @if ($errors->has('due_date'))
                        <span class="error">
                            {{ $errors->first('due_date') }}
                        </span>
                        @endif
                        <div class="field" style="padding-bottom: 24px">
                            <label for="reminder_date">Reminder Date</label>
                            <input class="date form-control" type="date" name="reminder_date" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                        </div>
                        @if ($errors->has('reminder_date'))
                        <span class="error">
                            {{ $errors->first('reminder_date') }}
                        </span>
                        @endif
                        <div class="field" style="padding-bottom: 24px">
                            <input type="submit" name="submit" value="Create Task">
                            <input name="project_id" type="hidden" value="{{$project -> id}}">
                        </div>
                    </form>
                </div>
            </div>
</div>


@endsection
