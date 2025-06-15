<!DOCTYPE html>
<html lang="en">
@include('partials.header')


<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
            @include('partials.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

                <!-- Topbar -->
                @include('partials.topbar')
                <!-- End of Topbar -->
                
            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('partials.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>





<script src="https://unpkg.com/cropperjs"></script>
<script>
    let cropper;
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');

    logoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            logoPreview.src = event.target.result;

            if (cropper) cropper.destroy(); // Hapus crop sebelumnya
            cropper = new Cropper(logoPreview, {
                aspectRatio: 1, // Square crop
                viewMode: 1,
                autoCropArea: 1
            });
        };
        reader.readAsDataURL(file);
    });

    // Crop image on form submit
    document.querySelector('form').addEventListener('submit', function (e) {
        if (cropper) {
            e.preventDefault();

            cropper.getCroppedCanvas().toBlob(function (blob) {
                const formData = new FormData(e.target);
                formData.set('logo', blob, 'cropped_logo.png');

                fetch(e.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => window.location.reload());
            });
        }
    });
</script>


@stack('scripts')

</body>

@include('partials.script')

</html>

<style>
    .btn-sm {
        font-size: 0.8rem;
    }
    img.logo-preview {
        border-radius: 4px;
        border: 1px solid #ddd;
        padding: 5px;
        margin-top: 10px;
    }

    #logoPreview {
        max-width: 100%;
        border: 1px solid #ddd;
        margin-top: 10px;
    }
</style>
