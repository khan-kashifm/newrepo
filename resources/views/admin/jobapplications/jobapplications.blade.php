@extends('front.layouts.app')
@section('main')

    <body data-instant-intensity="mousedown">

        <section class="section-5 bg-2">
            <div class="container py-5">
                <div class="row">
                    <div class="col">
                        <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Job Applications</li>
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
                        <div class="card border-0 shadow mb-4 p-3">
                            <div class="card-body card-form">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fs-4 mb-1">Jobs Applied</h3>
                                    </div>
                                    {{-- <div style="margin-top: -10px;">
                                        <a href="{{ route('account.createJob') }}" class="btn btn-primary">Post a Job</a>
                                    </div> --}}

                                </div>
                                <div class="table-responsive">
                                    <table class="table ">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">Title</th>
                                                <th scope="col">User</th>
                                                <th scope="col">Employer</th>
                                                <th scope="col">Applied Date</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-0">

                                            @if ($jobApplications->isNotEmpty())
                                                @foreach ($jobApplications as $jobApplication)
                                                    <tr class="active">
                                                        <td>
                                                            <div class="job-name fw-500">{{ $jobApplication->job->title }}
                                                            </div>
                                                            <div class="info1">{{ $jobApplication->job->jobType->name }}.
                                                                {{ $jobApplication->job->location }}
                                                            </div>
                                                        </td>
                                                        <td>{{ $jobApplication->user->name }}
                                                        </td>
                                                        <td>{{ $jobApplication->employer->name }}
                                                        </td>
                                                        <td>{{ \carbon\Carbon::parse($jobApplication->job->created_at)->format('d M, Y') }}
                                                        </td>
                                                        
                                                        <td>
                                                            @if ($jobApplication->job->status == 1)
                                                                <span class="btn btn-success" style="padding: 2px 8px; font-size: 12px;">Active</span>
                                                            @else
                                                                <span class="btn btn-danger" style="padding: 2px 8px; font-size: 12px;">Block</span>
                                                            @endif
                                                        </td> 
                                                        <td>    
                                                            <div class="float-mid" style="font-size: 1.5rem; padding: 10px;">
                                                                <ul>
                                                                    <a href="{{ route('jobDetail', $jobApplication->job_id ) }}">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a onclick="removeapplication({{ $jobApplication->job_id }});" href="#">
                                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                                    </a>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5">Job Applications not Found</td>

                                                </tr>
                                            @endif

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>

                        {{ $jobApplications->links() }}
                    </div>
                @endsection
