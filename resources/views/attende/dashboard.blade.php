@extends('layouts/dashboard-attende')

@section('dashboard-return')
<a class="navbar-brand ms-3" href="{{route('attende.dashboard')}}">Event Management System</a>

@endsection
@section('title')
    <title>Attende Dashboard</title>
@endsection

@section('role')
<p class="text-center fw-bold">Attende</p>
@endsection

@section('side-bar-ul')


@endsection

@section('content-dashboard')

     <!-- Dashboard Content -->
            <div class="container py-4">
                <h3 class="mb-4">Attende Dashboard</h3>
                <div class="row">

                    <!-- Manage Assessments Card -->
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h5 class="card-title">Manage Events</h5>
                                <p class="card-text">Go to Events</p>
                                <a href="{{route('attende.events.index')}}" class="btn btn-light">Manage Events</a>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
