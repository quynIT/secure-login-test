@extends('layout.app')

@section('content')
<div class="container1 h-min-screen">
    <div class="row justify-content-center">
        <div class="col-md-8 ">
            <div class="card shadow-lg ">
                <div class="card-body p-4 text-center h-full w-full justify-center">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.answer') }}" id="questionForm">
                        @csrf

                        <div class="text-center mb-1 ">
                            <img src="https://inkythuatso.com/uploads/thumbnails/800/2022/03/meo-cam-bong-hoa-tren-tay-manh-me-len-23-09-00-15.jpg" alt="Cute kitten with flowers" class="img-fluid rounded mb-4 p-5" style="max-width: 500px;">
                            <h3 class="fw-bold text-green-600">Leader có đẹp trai như lời đồn không?</h3>
                        </div>
                        
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-8 d-flex justify-content-center gap-3">
                                <button type="button" class="btn btn-success btn-choice bg-green-400" data-value="yes">
                                    Yes
                                </button>
                                <button type="button" class="btn btn-danger btn-choice bg-green-400" data-value="no">
                                    No
                                </button>
                                <input type="hidden" name="answer" id="answer_hidden">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container1 {
    min-height: 100vh; /* Chiều cao tối thiểu bằng 100% chiều cao của viewport */
    display: flex;
    align-items: center;
    justify-content: center;
}
    .card {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        width: 100%;
       
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }
    
    .text-pink {
        color: #ff69b4;
    }
    
    .btn-choice {
        width: 100px;
        border-radius: 8px;
        font-weight: bold;
        padding: 10px 0;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .btn-choice:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }
    
    h3 {
        font-size: 28px;
        margin-bottom: 20px;
    }
    
    /* Ensure the image is centered and responsive */
    img {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        height: auto;
    }
    
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle button clicks
    document.querySelectorAll('.btn-choice').forEach(function(button) {
        button.addEventListener('click', function() {
            // Set the hidden input value
            document.getElementById('answer_hidden').value = this.dataset.value;
            
            // Submit the form
            submitForm();
        });
    });

    function submitForm() {
        const form = document.getElementById('questionForm');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If successful, show message and redirect
                Swal.fire({
                    title: 'Tuyệt vời!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Tiếp tục'
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                // If "No" is selected, show error message and stay on page
                Swal.fire({
                    title: 'Không chính xác!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Thử lại'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra, vui lòng thử lại.',
                icon: 'error',
                confirmButtonText: 'Đóng'
            });
        });
    }
});
</script>

<!-- Add FontAwesome and SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection