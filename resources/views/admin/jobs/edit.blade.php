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
                                <li class="breadcrumb-item"><a href="{{ route('admin.jobs') }}">Jobs</a></li>
                                <li class="breadcrumb-item active">Edit Job</li>
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
                        <form action="{{ route('admin.updateJob', $job->id) }}" method="post" class="needs-validation"
                            novalidate>
                            @csrf
                            <div class="card border-0 shadow mb-4">
                                <div class="card-body card-form p-4">
                                    <h3 class="fs-4 mb-1">Edit Job Details</h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="title" class="mb-2">Title<span class="req">*</span></label>
                                            <input value="{{ $job->title }}" type="text" placeholder="Job Title"
                                                id="title" name="title" class="form-control" required>
                                            <div class="invalid-feedback">
                                                Please Enter a Title
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="category_id" class="mb-2">Category<span
                                                    class="req">*</span></label>
                                            <select name="category_id" id="category_id" class="form-control" required>
                                                <option value="">Select a Category</option>
                                                @if ($categories->isNotEmpty())
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ $job->category_id == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="invalid-feedback">
                                                Please Choose a Category
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="job_type" class="mb-2">Job Nature<span
                                                    class="req">*</span></label>
                                            <select name="job_type" id="job_type" class="form-control" required>
                                                @if ($jobTypes->isNotEmpty())
                                                    @foreach ($jobTypes as $jobType)
                                                        <option value="{{ $jobType->id }}"
                                                            {{ $job->job_type_id == $jobType->id ? 'selected' : '' }}>
                                                            {{ $jobType->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="invalid-feedback" id="feedback">
                                                Please Choose a Job Nature
                                            </div>
                                        </div>


                                        <div class="col-md-6  mb-4">
                                            <label for="vacancy" class="mb-2">Vacancy<span
                                                    class="req">*</span></label>
                                            <input value="{{ $job->vacancy }}" type="number" min="1"
                                                placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control"
                                                required>
                                            <div class="invalid-feedback" id="feedback">
                                                Please Enter a Vacancy
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-4 col-md-6">
                                            <label for="salary" class="mb-2">Salary</label>
                                            <input value="{{ $job->salary }}" type="text" placeholder="Salary"
                                                id="salary" name="salary" class="form-control">
                                        </div>

                                        <div class="mb-4 col-md-6">
                                            <label for="location" class="mb-2">Location </label>
                                            <input value="{{ $job->location }}" type="text" placeholder="location"
                                                id="location" name="location" class="form-control" required>
                                            <div class="invalid-feedback" id="feedback">
                                                Please Enter a Location
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-4 col-md-6">
                                            <div class="form-check">
                                                <input {{ $job->isFeatured == 1 ? 'checked' : '' }} class="form-check-input"
                                                    type="checkbox" value="1" id="isFeatured" name="isFeatured">
                                                <label class="form-check-label" for="isFeatured">
                                                    Featured
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-4 col-md-6">
                                            <div class="mb-4 col-md-6">
                                                <div class="form-check-inline">
                                                    <input {{ $job->status == 1 ? 'checked' : '' }} class="form-check-input"
                                                        type="radio" value="1" id="status-active" name="status">
                                                    <label class="form-check-label" for="status-active">
                                                        Active
                                                    </label>
                                                </div>
                                                <div class="form-check-inline">
                                                    <input {{ $job->status == 0 ? 'checked' : '' }} class="form-check-input"
                                                        type="radio" value="0" id="status-block" name="status">
                                                    <label class="form-check-label" for="status-block">
                                                        Block
                                                    </label>
                                                </div>
                                            </div>
                                             

                                        </div>
                                    </div>


                                    <div class="mb-4">
                                        <label for="description" class="mb-2">Description<span
                                                class="req">*</span></label>
                                        <textarea class="form-control" name="description" id="description" cols="5" rows="5"
                                            placeholder="Description" required> {{ $job->description }} </textarea>
                                        <div class="invalid-feedback" id="feedback">
                                            Please Enter Description
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="benefits" class="mb-2">Benefits</label>
                                        <textarea class="form-control" name="benefits" id="benefits" cols="5" rows="5"
                                            placeholder="Benefits">{{ $job->benefits }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="responsibility" class="mb-2">Responsibility</label>
                                        <textarea class="form-control" name="responsibility" id="responsibility" cols="5" rows="5"
                                            placeholder="Responsibility">{{ $job->responsibility }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="qualifications" class="mb-2">Qualifications</label>
                                        <textarea class="form-control" name="qualifications" id="qualifications" cols="5" rows="5"
                                            placeholder="Qualifications"> {{ $job->qualifications }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="experience" class="mb-2">Experience <span
                                                class="req">*</span></label>
                                        <select name="experience" id="experience">
                                            <option value="1" {{ $job->experience == 1 ? 'selected' : '' }}>1 Year
                                            </option>
                                            <option value="2" {{ $job->experience == 2 ? 'selected' : '' }}>2 Years
                                            </option>
                                            <option value="3"{{ $job->experience == 3 ? 'selected' : '' }}>3 Years
                                            </option>
                                            <option value="4"{{ $job->experience == 4 ? 'selected' : '' }}>4 Years
                                            </option>
                                            <option value="5"{{ $job->experience == 5 ? 'selected' : '' }}>5 Years
                                            </option>
                                            <option value="6"{{ $job->experience == 6 ? 'selected' : '' }}>6 Years
                                            </option>
                                            <option value="7"{{ $job->experience == 7 ? 'selected' : '' }}>7 Years
                                            </option>
                                            <option value="8"{{ $job->experience == 8 ? 'selected' : '' }}>8 Years
                                            </option>
                                            <option value="9"{{ $job->experience == 9 ? 'selected' : '' }}>9 Years
                                            </option>
                                            <option value="10"{{ $job->experience == 10 ? 'selected' : '' }}>10 Years
                                            </option>
                                            <option value="10_plus"{{ $job->experience == '10_plus' ? 'selected' : '' }}>
                                                10+ Years</option>
                                        </select>
                                    </div>


                                    <div class="mb-4">
                                        <label for="keywords" class="mb-2">Keywords</label>
                                        <input value="{{ $job->keywords }}" type="text" placeholder="keywords"
                                            id="keywords" name="keywords" class="form-control">
                                    </div>

                                    <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>
                                    <div class="row">
                                        <div class="mb-4 col-md-6">
                                            <label for="company_name" class="mb-2">Name<span
                                                    class="req">*</span></label>
                                            <input value="{{ $job->company_name }}" type="text"
                                                placeholder="Company Name" id="company_name" name="company_name"
                                                class="form-control" required>
                                            <div class="invalid-feedback">
                                                Please Enter a Company Name
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-6">
                                            <label for="company_location" class="mb-2">Location</label>
                                            <input value="{{ $job->company_location }}" type="text"
                                                placeholder="Company Location" id="company_location"
                                                name="company_location" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="website" class="mb-2">Website</label>
                                        <input value="{{ $job->company_website }}" type="text" placeholder="Website"
                                            id="company_website" name="company_website" class="form-control">
                                    </div>
                                </div>
                                <div class="card-footer p-4">
                                    <button type="submit" value="submit" class="btn btn-primary">Update Job</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </body>

    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
