
@extends('front.layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('main')

    <body data-instant-intensity="mousedown">

        <section class="section-5 bg-2">
            <div class="container py-5">
                <div class="row">
                    <div class="col">
                        <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Jobs</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        @include('admin.sidebar')
                    </div>
                    <div class="col-lg-9">
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session::get('success') }}<button type="button" class="btn-close"
                                    data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session::get('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="card border-0 shadow mb-4">
                            <div class="card-body card-form">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fs-4 mb-1">Jobs</h3>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table ">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Created By</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Status </th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-0">

                                            @if ($jobs->isNotEmpty())
                                                @foreach ($jobs as $job)
                                                    <tr class="active">
                                                        <td>
                                                            <div class="job-id  fw-500">{{ $job->id }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="job-title fw-500">{{ $job->title }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="job-name fw-500">{{ $job->User->name }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="job-date fw-500">
                                                                {{ \carbon\Carbon::parse($job->created_at)->format('d M, Y') }}
                                                            </div>
                                                        </td>

                                                        <td>
                                                            @if ($job->status == 1)
                                                                <span class="btn btn-success" style="padding: 2px 8px; font-size: 12px;">Active</span>
                                                            @else
                                                                <span class="btn btn-danger" style="padding: 2px 8px; font-size: 12px;">Block</span>
                                                            @endif
                                                        </td> 
                                                        
                                                         


                                                        <td>
                                                            <div class="float-mid"
                                                                style="font-size: 1.5rem; padding: 10px;">
                                                                <ul style="list-style-type: none; padding: 0;">
                                                                    <a href="{{ route('admin.editJob', $job->id) }}"
                                                                        style="margin-right: 5px;">
                                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a onclick="deleteJob({{ $job->id }});"
                                                                        href="#">
                                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                                    </a>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    {{ $jobs->links() }}
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <script>
                function deleteJob(id) {
                    Swal.fire({
                        title: "Are you sure you want to delete this job?",
                        text: "Job will be permanently deleted",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('admin.deleteJob') }}',
                                type: 'post',
                                data: {
                                    id: id
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Job has been Deleted Successfully.",
                                        icon: "success"
                                    }).then(() => {

                                        window.location.href = '{{ route('admin.jobs') }}';
                                    });

                                }
                            });

                        }
                    });
                }
            </script>
        @endsection
