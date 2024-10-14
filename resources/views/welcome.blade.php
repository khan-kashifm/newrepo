<script>
    function deleteJob(jobId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: '{{ route("account.deleteJob") }}',
                    type: 'post',
                    data: { jobId: jobId },
                    dataType: 'json'
                    success: function(response){
                        windown.location.href='{{ route('account.myjobs') }}';
                    }


                });

                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
            }
        });
    }
</script> --}}
