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
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        @include('admin.sidebar')
                    </div>
                    <div class="col-lg-9">
                        <div class="card border-0 shadow mb-4">
                            <div class="card-body card-form">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fs-4 mb-1">Users</h3>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table ">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Mobile</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-0">

                                            @if ($users->isNotEmpty())
                                                @foreach ($users as $user)
                                                    <tr class="active">
                                                        <td>
                                                            <div class="job-name fw-500">{{ $user->id }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="info1">{{ $user->name }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="job-name fw-500">{{ $user->email }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="info1">{{ $user->mobile }}</div>
                                                        </td>

                                                        <td>
                                                            <div class="float-mid"
                                                                style="font-size: 1.5rem; padding: 10px;">
                                                                <ul style="list-style-type: none; padding: 0;">
                                                                    <a href="#" style="margin-right: 5px;">
                                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a onclick="deleteUser({{ $user->id }});"
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
                                    {{ $users->links() }}
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <script>
                function deleteUser(id) {


                    Swal.fire({
                        title: "Are you sure you want to delete this user?",
                        text: "User will be permanently deleted",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('admin.deleteUser') }}',
                                type: 'post',
                                data: {
                                    id: id
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "User has been removed.",
                                        icon: "success"
                                    }).then(() => {

                                        window.location.href = '{{ route('admin.users') }}';
                                    });

                                }
                            });

                        }
                    });
                }
            </script>

        @endsection
